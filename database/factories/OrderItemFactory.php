<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quantity' => fake()->numberBetween(1, 5),
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'price' => fake()->randomFloat(2, 2, 2),
            'discount_price' => fake()->randomFloat(2, 2, 2),
        ];
    }
}
