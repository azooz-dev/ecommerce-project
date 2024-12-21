<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_number' => fake()->uuid(),
            'total_amount' => fake()->randomFloat(2, 2, 2),
            'payment_method' => fake()->randomElement(['cash', 'card']),
            'address' => fake()->address(),
            'status' => fake()->randomElement(['1', '0']),
            'user_id' => User::factory(),
        ];
    }
}