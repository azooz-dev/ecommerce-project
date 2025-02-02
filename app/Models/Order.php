<?php

namespace App\Models;

use App\Models\Coupon;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    const PENDING_ORDER = 'قيد الإنتظار';
    const COMPLETED_ORDER = 'مكتمل';
    const CANCELED_ORDER = 'ملغي';

    protected $fillable = [
        'order_number',
        'payment_method',
        'address',
        'status',
        'user_id',
        'coupon_id',
    ];

    protected $attributes = [
        'status' => self::PENDING_ORDER,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderNumber(): string
    {
        return 'ORD-' . strtoupper(uniqid());
    }

    public function getTotalAmountAttribute($value)
    {
        return number_format($value, 2);
    }

    // In Order model
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($order) {
            // Ensure that the orderItems relationship is loaded
            $order->load('orderItems');

            // Calculate the total amount before saving the order
            $order->calculateTotalAmount();
        });
    }

    public function calculateTotalAmount()
    {
        $this->total_amount = 0;
        // Calculate the total amount by summing up each order item's total
        $this->total_amount = $this->orderItems->sum(function ($orderItem) {
            if ($orderItem->price && $orderItem->price > 0) {
                return $orderItem->price;
            }
        });

        // Apply the coupon discount if it exists
        if ($this->coupon) {
            $this->total_amount -= ($this->total_amount * ($this->coupon->discount / 100));
        }
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
