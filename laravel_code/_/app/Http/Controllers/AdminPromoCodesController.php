<?php

namespace App\Http\Controllers;

use DB;
use App\Models\PromoCodes;
use App\Models\PromoCodeHistories;
use App\Models\PromoCodeUsages;
use App\Services\PromoCodeService;

class AdminPromoCodesController extends Controller
{
    protected $promoCodeService;

    public function __construct()
    {
        $this->promoCodeService = app(PromoCodeService::class);
    }

    public function index()
    {
        abort_unless(auth()->user()->hasPermission('subscriptions'), 403);

        $codes = PromoCodes::with('creator:id,username,name')
            ->latest()
            ->paginate(20);

        $this->promoCodeService->logExpiredForCollection($codes->getCollection());

        $promoCodeIds = $codes->pluck('id')->all();
        $this->promoCodeService->reconcilePendingUsages($promoCodeIds);
        $stats = $this->statsForCodes($promoCodeIds);

        return view('admin.promo-codes', [
            'codes' => $codes,
            'stats' => $stats,
            'recentUsages' => $this->recentUsagesForCodes($promoCodeIds),
            'recentHistories' => $this->recentHistoriesForCodes($promoCodeIds),
        ]);
    }

    public function disable($id)
    {
        abort_unless(auth()->user()->hasPermission('subscriptions'), 403);

        $promoCode = PromoCodes::findOrFail($id);
        $oldValues = $promoCode->toArray();

        $promoCode->is_active = 'no';
        $promoCode->disabled_at = now();
        $promoCode->disabled_by_admin_id = auth()->id();
        $promoCode->save();

        $this->promoCodeService->createHistory(
            $promoCode->id,
            auth()->id(),
            'admin',
            'disabled',
            $oldValues,
            $promoCode->fresh()->toArray()
        );

        return back()->with('status', 'Promo code disabled successfully.');
    }

    protected function statsForCodes(array $promoCodeIds)
    {
        if (! count($promoCodeIds)) {
            return collect();
        }

        return PromoCodeUsages::select(
            'promo_code_id',
            DB::raw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as usage_count"),
            DB::raw("SUM(CASE WHEN status = 'completed' THEN discount_amount ELSE 0 END) as total_discount_amount"),
            DB::raw("SUM(CASE WHEN status = 'completed' THEN final_paid_amount ELSE 0 END) as revenue_generated"),
            DB::raw("COUNT(DISTINCT CASE WHEN status = 'completed' THEN user_id ELSE NULL END) as subscriber_count"),
            DB::raw("SUM(CASE WHEN status = 'completed' THEN creator_earning_impact ELSE 0 END) as creator_earnings_impact")
        )
            ->whereIn('promo_code_id', $promoCodeIds)
            ->groupBy('promo_code_id')
            ->get()
            ->keyBy('promo_code_id');
    }

    protected function recentUsagesForCodes(array $promoCodeIds)
    {
        if (! count($promoCodeIds)) {
            return collect();
        }

        $recentUsages = collect();

        foreach ($promoCodeIds as $promoCodeId) {
            $recentUsages->put(
                $promoCodeId,
                PromoCodeUsages::with([
                    'user:id,username,name,hide_name',
                    'plan:id,name,interval',
                ])
                    ->where('promo_code_id', $promoCodeId)
                    ->latest('used_at')
                    ->latest('id')
                    ->take(10)
                    ->get()
            );
        }

        return $recentUsages;
    }

    protected function recentHistoriesForCodes(array $promoCodeIds)
    {
        if (! count($promoCodeIds)) {
            return collect();
        }

        $recentHistories = collect();

        foreach ($promoCodeIds as $promoCodeId) {
            $recentHistories->put(
                $promoCodeId,
                PromoCodeHistories::with('actor:id,username,name,hide_name')
                    ->where('promo_code_id', $promoCodeId)
                    ->latest()
                    ->take(10)
                    ->get()
            );
        }

        return $recentHistories;
    }
}
