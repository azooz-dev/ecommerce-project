<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    const AVAILABLE_PRODUCT = 'available';
    const UNAVAILABLE_PRODUCT = 'unavailable';

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'price',
        'price_discount',
        'category_id',
        'status',
    ];

    public function isAvailable()
    {
        return $this->status == self::AVAILABLE_PRODUCT;
    }
}
