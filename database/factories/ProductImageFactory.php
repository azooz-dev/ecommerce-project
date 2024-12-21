<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

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
            'image_url' => fake()->randomElement([Storage::url('product_images/first-image.jpg'), Storage::url('product_images/second-image.jpg'), Storage::url('product_images/third-image.png')]),
            'alt_text' => fake()->sentence(),
        ];
    }
}
