<?php

namespace App\Services;

use App\Models\PromoCodeHistories;
use App\Models\PromoCodes;
use App\Models\PromoCodeUsages;
use Carbon\Carbon;

class PromoCodeService
{
    public function normalizeCode(?string $code): string
    {
        return strtoupper(trim((string) $code));
    }

    public function findCreatorCode(int $creatorId, ?string $code): ?PromoCodes
    {
        $normalizedCode = $this->normalizeCode($code);

        if ($normalizedCode === '') {
            return null;
        }

        return PromoCodes::where('creator_id', $creatorId)
            ->where('normalized_code', $normalizedCode)
            ->first();
    }

    public function validateForCheckout(int $creatorId, int $userId, ?string $code, float $originalAmount): array
    {
        $promoCode = $this->findCreatorCode($creatorId, $code);

        if (! $promoCode) {
            return $this->failure('invalid');
        }

        if ($promoCode->is_active !== 'yes') {
            return $this->failure('disabled', $promoCode);
        }

        if ($promoCode->expires_at && $promoCode->expires_at->lt(Carbon::now())) {
            $this->logExpiredIfNeeded($promoCode);
            return $this->failure('expired', $promoCode);
        }

        if (! is_null($promoCode->usage_limit_total) && $promoCode->used_count >= $promoCode->usage_limit_total) {
            return $this->failure('limit_total_reached', $promoCode);
        }

        if (! is_null($promoCode->usage_limit_per_user)) {
            $usageCount = PromoCodeUsages::where('promo_code_id', $promoCode->id)
                ->where('user_id', $userId)
                ->where('status', 'completed')
                ->count();

            if ($usageCount >= $promoCode->usage_limit_per_user) {
                return $this->failure('limit_per_user_reached', $promoCode);
            }
        }

        return [
            'valid' => true,
            'reason' => null,
            'promo_code' => $promoCode,
            'pricing' => $this->buildPricingSnapshot($promoCode, $originalAmount),
        ];
    }

    public function buildPricingSnapshot(PromoCodes $promoCode, float $originalAmount): array
    {
        $discountAmount = $promoCode->discount_type === 'percentage'
            ? round(($originalAmount * $promoCode->discount_value) / 100, 2)
            : round($promoCode->discount_value, 2);

        $discountAmount = min($discountAmount, round($originalAmount, 2));

        return [
            'original_amount' => round($originalAmount, 2),
            'discount_amount' => $discountAmount,
            'charged_amount' => round(max($originalAmount - $discountAmount, 0), 2),
        ];
    }

    public function canEditDiscount(PromoCodes $promoCode): bool
    {
        return is_null($promoCode->first_used_at);
    }

    public function buildNetEarningsSnapshot(
        float $chargedAmount,
        array $baseEarnings,
        ?float $paymentFee = null,
        ?float $paymentFeeCents = null
    ): array
    {
        $chargedAmount = round($chargedAmount, 2);

        if (! is_null($paymentFee)) {
            $processorNet = round(max($chargedAmount - ($chargedAmount * $paymentFee / 100) - (float) $paymentFeeCents, 0), 2);
        } else {
            $processorNet = $chargedAmount;
        }

        $adminEarning = min(round((float) $baseEarnings['admin'], 2), $processorNet);
        $creatorEarning = round(max($processorNet - $adminEarning, 0), 2);

        return [
            'user' => $creatorEarning,
            'admin' => $adminEarning,
            'percentageApplied' => $baseEarnings['percentageApplied'] ?? null,
            'processor_net' => $processorNet,
        ];
    }

    public function createUsage(PromoCodes $promoCode, array $attributes): PromoCodeUsages
    {
        return PromoCodeUsages::create(array_merge([
            'promo_code_id' => $promoCode->id,
            'creator_id' => $promoCode->creator_id,
            'status' => 'pending',
        ], $attributes));
    }

    public function markUsageCompleted(PromoCodeUsages $usage, array $attributes = []): PromoCodeUsages
    {
        $usage->fill(array_merge([
            'status' => 'completed',
            'used_at' => now(),
        ], $attributes));
        $usage->save();

        $promoCode = $usage->promoCode;

        if ($promoCode) {
            $promoCode->used_count = PromoCodeUsages::where('promo_code_id', $promoCode->id)
                ->where('status', 'completed')
                ->count();

            if (is_null($promoCode->first_used_at)) {
                $promoCode->first_used_at = now();
            }

            $promoCode->save();
        }

        return $usage;
    }

    public function markUsageFailed(PromoCodeUsages $usage, ?string $notes = null): PromoCodeUsages
    {
        $usage->status = 'failed';
        $usage->save();

        if ($notes) {
            $this->createHistory($usage->promo_code_id, null, 'system', 'usage_failed', null, null, $notes);
        }

        return $usage;
    }

    public function createHistory(
        int $promoCodeId,
        ?int $actorUserId,
        string $actorRole,
        string $eventType,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $notes = null
    ): PromoCodeHistories {
        return PromoCodeHistories::create([
            'promo_code_id' => $promoCodeId,
            'actor_user_id' => $actorUserId,
            'actor_role' => $actorRole,
            'event_type' => $eventType,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'notes' => $notes,
        ]);
    }

    public function logExpiredIfNeeded(PromoCodes $promoCode): void
    {
        if (! $promoCode->expires_at || $promoCode->expires_at->gte(Carbon::now())) {
            return;
        }

        $alreadyLogged = PromoCodeHistories::where('promo_code_id', $promoCode->id)
            ->where('event_type', 'expired')
            ->exists();

        if ($alreadyLogged) {
            return;
        }

        $this->createHistory(
            $promoCode->id,
            null,
            'system',
            'expired',
            null,
            [
                'expired_at' => $promoCode->expires_at->toDateTimeString(),
            ]
        );
    }

    public function logExpiredForCollection($promoCodes): void
    {
        foreach ($promoCodes as $promoCode) {
            if ($promoCode instanceof PromoCodes) {
                $this->logExpiredIfNeeded($promoCode);
            }
        }
    }

    protected function failure(string $reason, ?PromoCodes $promoCode = null): array
    {
        return [
            'valid' => false,
            'reason' => $reason,
            'promo_code' => $promoCode,
            'pricing' => null,
        ];
    }
}
