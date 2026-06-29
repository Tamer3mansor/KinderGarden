<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FeePlanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'                => fake()->randomElement(['Basic Plan', 'Standard Plan', 'Premium Plan', 'Summer Plan']),
            'amount'              => fake()->randomFloat(2, 1000, 10000),
            'duration_months'     => fake()->numberBetween(1, 12),
            'discount_percentage' => fake()->randomFloat(2, 0, 50),
        ];
    }
}
