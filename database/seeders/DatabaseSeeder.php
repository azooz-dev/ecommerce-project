<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Favorite;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSize;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(100)->create();
        Category::factory(30)->create();
        Product::factory(1000)->create();
        Favorite::factory(20)->create();
        Order::factory(100)->create();
        ProductImage::factory(1000)->create();
        ProductSize::factory(1000)->create();
        OrderItem::factory(150)->create();
        Coupon::factory(30)->create();
    }
}
