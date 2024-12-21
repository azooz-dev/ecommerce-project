<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word,
            'description' => fake()->sentence,
            'quantity' => fake()->numberBetween(1, 100),
            'price' => fake()->randomFloat(1, 2, 2),
            'price_discount' => fake()->randomFloat(1, 2, 2),
            'category_id' => fake()->numberBetween(1, 5),
            'status' => fake()->randomElement([Product::AVAILABLE_PRODUCT, Product::UNAVAILABLE_PRODUCT]),
        ];
    }
}
