<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductSize extends Model
{
    use HasFactory;

    protected $fillable = [
        'size_product',
        'product_id',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
