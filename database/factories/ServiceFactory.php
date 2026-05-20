<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'icon' => 'fa-camera',
            'starting_price' => $this->faker->randomFloat(2, 500, 5000),
            'features' => [$this->faker->sentence(), $this->faker->sentence()],
            'order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
