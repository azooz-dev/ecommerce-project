<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => rand(1, 20),
            'image_name' => fake()->randomElement(['first-image.jpg', 'second-image.jpg', 'third-image.png']),
            'alt_text' => fake()->sentence(),
        ];
    }
}
