<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'discount' => $this->faker->randomFloat(2, 0, 100),
            'coupon_validity' => $this->faker->dateTimeBetween('now', '+1 year'),
            'status' => $this->faker->randomElement([Coupon::ACTIVE_COUPON, Coupon::INACTIVE_COUPON]),
        ];
    }
}
