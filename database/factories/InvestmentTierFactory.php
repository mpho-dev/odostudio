<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvestmentTier>
 */
class InvestmentTierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tier_label' => $this->faker->word(),
            'name' => $this->faker->words(2, true),
            'price' => $this->faker->randomFloat(2, 100, 5000),
            'price_suffix' => $this->faker->optional()->word(),
            'is_featured' => $this->faker->boolean(),
            'badge_label' => $this->faker->optional()->word(),
            'features' => [
                $this->faker->sentence(),
                $this->faker->sentence(),
                $this->faker->sentence(),
            ],
            'order' => $this->faker->randomNumber(2),
        ];
    }
}
