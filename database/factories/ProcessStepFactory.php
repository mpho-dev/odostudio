<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProcessStep>
 */
class ProcessStepFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'step_number' => $this->faker->unique()->numberBetween(1, 100),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'display_order' => $this->faker->numberBetween(1, 100),
        ];
    }
}
