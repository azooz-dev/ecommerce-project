<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'price',
        'discount_price',
        'order_id',
        'product_id'
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
