<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCodeHistories extends Model
{
    protected $fillable = [
        'promo_code_id',
        'actor_user_id',
        'actor_role',
        'event_type',
        'old_values',
        'new_values',
        'notes',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function promoCode()
    {
        return $this->belongsTo(PromoCodes::class, 'promo_code_id');
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }
}
