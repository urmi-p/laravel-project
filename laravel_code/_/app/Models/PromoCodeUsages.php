<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCodeUsages extends Model
{
    protected $fillable = [
        'promo_code_id',
        'creator_id',
        'user_id',
        'subscription_id',
        'transaction_id',
        'plan_id',
        'plan_interval',
        'gateway_name',
        'gateway_reference',
        'checkout_token',
        'original_amount',
        'discount_amount',
        'charged_amount',
        'creator_earning_impact',
        'platform_commission_amount',
        'gateway_fee_amount',
        'final_paid_amount',
        'creator_net_amount',
        'admin_net_amount',
        'tax_amount',
        'status',
        'used_at',
    ];

    protected $casts = [
        'used_at' => 'datetime',
    ];

    public function promoCode()
    {
        return $this->belongsTo(PromoCodes::class, 'promo_code_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subscription()
    {
        return $this->belongsTo(Subscriptions::class, 'subscription_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transactions::class, 'transaction_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plans::class, 'plan_id');
    }
}
