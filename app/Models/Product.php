<?php

namespace App\Models;

use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductSize;
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function productSizes()
    {
        return $this->hasMany(ProductSize::class);
    }
}
