<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCodes extends Model
{
    protected $fillable = [
        'creator_id',
        'code',
        'normalized_code',
        'discount_type',
        'discount_value',
        'expires_at',
        'usage_limit_total',
        'usage_limit_per_user',
        'used_count',
        'is_active',
        'first_used_at',
        'disabled_at',
        'disabled_by_admin_id',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'first_used_at' => 'datetime',
        'disabled_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function disabledByAdmin()
    {
        return $this->belongsTo(User::class, 'disabled_by_admin_id');
    }

    public function usages()
    {
        return $this->hasMany(PromoCodeUsages::class, 'promo_code_id');
    }

    public function histories()
    {
        return $this->hasMany(PromoCodeHistories::class, 'promo_code_id');
    }
}
