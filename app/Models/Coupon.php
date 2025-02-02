<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    const ACTIVE_COUPON = '1';
    const INACTIVE_COUPON = '0';

    protected $fillable = [
        'name',
        'coupon_validity',
        'discount',
        'status'
    ];

    protected $casts = [
        'coupon_validity' => 'date',
        'discount' => 'decimal:2',
    ];

    public function isActive(): bool
    {
        return $this->status === self::ACTIVE_COUPON;
    }
}
