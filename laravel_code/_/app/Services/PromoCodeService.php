<?php

namespace App\Services;

use App\Helper;
use App\Models\PromoCodeHistories;
use App\Models\PromoCodes;
use App\Models\PromoCodeUsages;
use App\Models\Subscriptions;
use App\Models\TaxRates;
use App\Models\Transactions;
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
        $originalGrossAmount = $this->grossAmount($originalAmount);
        $discountAmount = $promoCode->discount_type === 'percentage'
            ? round(($originalGrossAmount * $promoCode->discount_value) / 100, 2)
            : round($promoCode->discount_value, 2);

        return $this->buildSubscriptionPricing($originalAmount, $discountAmount);
    }

    public function buildSubscriptionPricing(
        float $originalAmount,
        float $discountAmount = 0.0,
        ?float $paymentFee = null,
        ?float $paymentFeeCents = null,
        ?float $taxRateOverride = null
    ): array {
        $originalAmount = $this->grossAmount($originalAmount, $taxRateOverride);
        $taxRate = $this->taxRate($taxRateOverride);
        $discountAmount = min(round($discountAmount, 2), $originalAmount);
        $subtotalWithTax = round(max($originalAmount - $discountAmount, 0), 2);

        $chargedAmount = $subtotalWithTax;
        $taxAmount = 0.00;

        if ($subtotalWithTax > 0 && $taxRate > 0) {
            $chargedAmount = round($subtotalWithTax / (1 + ($taxRate / 100)), 2);
            $taxAmount = round(max($subtotalWithTax - $chargedAmount, 0), 2);
        }

        $gatewayFeeAmount = 0.00;

        if (! is_null($paymentFee) && $subtotalWithTax > 0) {
            $gatewayFeeAmount = round(
                ($subtotalWithTax * $paymentFee / 100) + (float) $paymentFeeCents,
                2
            );
        }

        return [
            'original_amount' => $originalAmount,
            'discount_amount' => $discountAmount,
            'charged_amount' => $chargedAmount,
            'tax_amount' => $taxAmount,
            'subtotal_with_tax' => $subtotalWithTax,
            'gateway_fee_amount' => $gatewayFeeAmount,
            'total_due' => round($subtotalWithTax + $gatewayFeeAmount, 2),
        ];
    }

    protected function grossAmount(float $amount, ?float $taxRateOverride = null): float
    {
        $amount = round($amount, 2);
        $taxRate = $this->taxRate($taxRateOverride);

        if ($amount <= 0 || $taxRate <= 0) {
            return $amount;
        }

        return round($amount + (($amount * $taxRate) / 100), 2);
    }

    public function resolveTaxRateFromIds(?string $taxes): float
    {
        if (! $taxes) {
            return 0.0;
        }

        $taxIds = collect(explode('_', $taxes))
            ->filter(function ($value) {
                return $value !== null && $value !== '';
            })
            ->map(function ($value) {
                return (int) $value;
            })
            ->filter();

        if ($taxIds->isEmpty()) {
            return 0.0;
        }

        return round((float) TaxRates::whereIn('id', $taxIds)->sum('percentage'), 4);
    }

    protected function taxRate(?float $taxRateOverride = null): float
    {
        if (! is_null($taxRateOverride)) {
            return max(round($taxRateOverride, 4), 0);
        }

        $user = auth()->user();

        if (! $user) {
            return 0.0;
        }

        return (float) $user->isTaxable()->sum('percentage');
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

    public function buildUsageCompletionSnapshot(
        float $finalPaidAmount,
        float $gatewayFeeAmount,
        array $originalEarnings,
        array $settledEarnings
    ): array {
        $originalCreatorNet = round((float) ($originalEarnings['user'] ?? 0), 2);
        $settledCreatorNet = round((float) ($settledEarnings['user'] ?? 0), 2);

        return [
            'gateway_fee_amount' => round($gatewayFeeAmount, 2),
            'final_paid_amount' => round($finalPaidAmount, 2),
            'creator_net_amount' => $settledCreatorNet,
            'admin_net_amount' => round((float) ($settledEarnings['admin'] ?? 0), 2),
            'creator_earning_impact' => round(max($originalCreatorNet - $settledCreatorNet, 0), 2),
        ];
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

    public function reconcilePendingUsages(array $promoCodeIds = [], int $staleMinutes = 15): int
    {
        $query = PromoCodeUsages::where('status', 'pending');

        if (! empty($promoCodeIds)) {
            $query->whereIn('promo_code_id', $promoCodeIds);
        }

        $pendingUsages = $query->get();

        if ($pendingUsages->isEmpty()) {
            return 0;
        }

        $subscriptionIds = $pendingUsages->pluck('subscription_id')->filter()->unique()->values();
        $transactionIds = $pendingUsages->pluck('transaction_id')->filter()->unique()->values();

        $subscriptions = $subscriptionIds->isEmpty()
            ? collect()
            : Subscriptions::whereIn('id', $subscriptionIds)->get()->keyBy('id');

        $transactions = $transactionIds->isEmpty()
            ? collect()
            : Transactions::whereIn('id', $transactionIds)->get()->keyBy('id');

        $threshold = Carbon::now()->subMinutes($staleMinutes);
        $reconciled = 0;

        foreach ($pendingUsages as $usage) {
            $subscription = $usage->subscription_id ? $subscriptions->get($usage->subscription_id) : null;
            $transaction = $usage->transaction_id ? $transactions->get($usage->transaction_id) : null;
            $failureReason = null;

            if ($transaction && (string) $transaction->approved === '2') {
                $failureReason = 'Checkout canceled before payment completion.';
            } elseif ($subscription && ($subscription->cancelled === 'yes' || $subscription->stripe_status === 'canceled')) {
                $failureReason = 'Subscription checkout was canceled before payment completion.';
            } elseif (
                $usage->gateway_name === 'Stripe'
                && $subscription
                && $subscription->stripe_status === 'incomplete'
                && $usage->created_at
                && $usage->created_at->lte($threshold)
            ) {
                $failureReason = 'Stripe payment confirmation was not completed.';
            } elseif (
                ! $subscription
                && ! $transaction
                && $usage->created_at
                && $usage->created_at->lte($threshold)
            ) {
                $failureReason = 'Pending checkout expired before payment completion.';
            }

            if (! $failureReason) {
                continue;
            }

            $this->closePendingCheckoutArtifacts($usage, $subscription, $transaction);
            $this->markUsageFailed($usage, $failureReason);
            $reconciled++;
        }

        return $reconciled;
    }

    protected function closePendingCheckoutArtifacts(
        PromoCodeUsages $usage,
        ?Subscriptions $subscription,
        ?Transactions $transaction
    ): void {
        if ($transaction && (string) $transaction->approved === '0') {
            $transaction->approved = '2';
            $transaction->save();
        }

        if (! $subscription) {
            return;
        }

        $hasApprovedTransaction = Transactions::where('subscriptions_id', $subscription->id)
            ->where('type', 'subscription')
            ->where('approved', '1')
            ->exists();

        if ($hasApprovedTransaction) {
            return;
        }

        if ($subscription->cancelled !== 'yes') {
            $subscription->cancelled = 'yes';
        }

        if ($subscription->stripe_status !== 'active') {
            $subscription->stripe_status = 'canceled';
        }

        $subscription->save();

        Transactions::where('subscriptions_id', $subscription->id)
            ->where('type', 'subscription')
            ->where('approved', '0')
            ->update(['approved' => '2']);
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
