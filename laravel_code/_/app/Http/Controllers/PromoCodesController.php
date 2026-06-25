<?php

namespace App\Http\Controllers;

use DB;
use App\Models\PromoCodes;
use App\Models\PromoCodeHistories;
use App\Models\PromoCodeUsages;
use Illuminate\Http\Request;
use App\Services\PromoCodeService;
use Illuminate\Support\Facades\Validator;

class PromoCodesController extends Controller
{
    protected $request;
    protected $promoCodeService;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->promoCodeService = app(PromoCodeService::class);
    }

    public function index()
    {
        abort_unless(auth()->user()->verified_id === 'yes', 403);

        $codes = PromoCodes::where('creator_id', auth()->id())
            ->latest()
            ->paginate(10);

        $this->promoCodeService->logExpiredForCollection($codes->getCollection());

        $promoCodeIds = $codes->pluck('id')->all();
        $stats = $this->statsForCodes($promoCodeIds);

        return view('users.promo-codes', [
            'codes' => $codes,
            'stats' => $stats,
            'recentUsages' => $this->recentUsagesForCodes($promoCodeIds),
            'recentHistories' => $this->recentHistoriesForCodes($promoCodeIds),
        ]);
    }

    public function store()
    {
        abort_unless(auth()->user()->verified_id === 'yes', 403);

        $validator = Validator::make($this->request->all(), [
            'code' => 'required|string|max:100',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0.01',
            'expires_at' => 'nullable|date',
            'usage_limit_total' => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'is_active' => 'required|in:yes,no',
        ]);

        $validator->after(function ($validator) {
            $normalizedCode = $this->promoCodeService->normalizeCode($this->request->code);

            if (PromoCodes::where('creator_id', auth()->id())->where('normalized_code', $normalizedCode)->exists()) {
                $validator->errors()->add('code', __('general.promo_code_already_exists'));
            }

            if ($this->request->discount_type === 'percentage' && (float) $this->request->discount_value > 100) {
                $validator->errors()->add('discount_value', __('general.promo_percentage_max_100'));
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $promoCode = PromoCodes::create([
            'creator_id' => auth()->id(),
            'code' => trim($this->request->code),
            'normalized_code' => $this->promoCodeService->normalizeCode($this->request->code),
            'discount_type' => $this->request->discount_type,
            'discount_value' => round((float) $this->request->discount_value, 2),
            'expires_at' => $this->request->expires_at ?: null,
            'usage_limit_total' => $this->request->usage_limit_total ?: null,
            'usage_limit_per_user' => $this->request->usage_limit_per_user ?: null,
            'is_active' => $this->request->is_active,
            'disabled_at' => $this->request->is_active === 'no' ? now() : null,
        ]);

        $this->promoCodeService->createHistory(
            $promoCode->id,
            auth()->id(),
            'creator',
            'created',
            null,
            $promoCode->toArray()
        );

        return back()->with('status', __('general.promo_code_created_successfully'));
    }

    public function update($id)
    {
        abort_unless(auth()->user()->verified_id === 'yes', 403);

        $promoCode = PromoCodes::where('creator_id', auth()->id())->findOrFail($id);
        $canEditDiscount = $this->promoCodeService->canEditDiscount($promoCode);

        $rules = [
            'expires_at' => 'nullable|date',
            'usage_limit_total' => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'is_active' => 'required|in:yes,no',
        ];

        if ($canEditDiscount) {
            $rules['code'] = 'required|string|max:100';
            $rules['discount_type'] = 'required|in:fixed,percentage';
            $rules['discount_value'] = 'required|numeric|min:0.01';
        }

        $validator = Validator::make($this->request->all(), $rules);

        $validator->after(function ($validator) use ($promoCode, $canEditDiscount) {
            if (! $canEditDiscount) {
                return;
            }

            $normalizedCode = $this->promoCodeService->normalizeCode($this->request->code);

            if (PromoCodes::where('creator_id', auth()->id())
                ->where('normalized_code', $normalizedCode)
                ->where('id', '<>', $promoCode->id)
                ->exists()) {
                $validator->errors()->add('code', __('general.promo_code_already_exists'));
            }

            if ($this->request->discount_type === 'percentage' && (float) $this->request->discount_value > 100) {
                $validator->errors()->add('discount_value', __('general.promo_percentage_max_100'));
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $oldValues = $promoCode->toArray();

        if ($canEditDiscount) {
            $promoCode->code = trim($this->request->code);
            $promoCode->normalized_code = $this->promoCodeService->normalizeCode($this->request->code);
            $promoCode->discount_type = $this->request->discount_type;
            $promoCode->discount_value = round((float) $this->request->discount_value, 2);
        }

        $promoCode->expires_at = $this->request->expires_at ?: null;
        $promoCode->usage_limit_total = $this->request->usage_limit_total ?: null;
        $promoCode->usage_limit_per_user = $this->request->usage_limit_per_user ?: null;
        $promoCode->is_active = $this->request->is_active;
        $promoCode->disabled_at = $this->request->is_active === 'no' ? now() : null;
        $promoCode->save();

        $this->promoCodeService->createHistory(
            $promoCode->id,
            auth()->id(),
            'creator',
            'updated',
            $oldValues,
            $promoCode->fresh()->toArray()
        );

        return back()->with('status', __('general.promo_code_updated_successfully'));
    }

    public function disable($id)
    {
        abort_unless(auth()->user()->verified_id === 'yes', 403);

        $promoCode = PromoCodes::where('creator_id', auth()->id())->findOrFail($id);
        $oldValues = $promoCode->toArray();

        $promoCode->is_active = 'no';
        $promoCode->disabled_at = now();
        $promoCode->save();

        $this->promoCodeService->createHistory(
            $promoCode->id,
            auth()->id(),
            'creator',
            'disabled',
            $oldValues,
            $promoCode->fresh()->toArray()
        );

        return back()->with('status', __('general.promo_code_disabled_successfully'));
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
            DB::raw("SUM(CASE WHEN status = 'completed' THEN charged_amount ELSE 0 END) as revenue_generated"),
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
