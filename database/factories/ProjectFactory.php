<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'client' => $this->faker->company(),
            'location' => $this->faker->city(),
            'description' => $this->faker->paragraph(),
            'category' => $this->faker->randomElement(['Weddings', 'Commercial', 'Portraits']),
            'is_featured' => $this->faker->boolean(20),
            'order' => $this->faker->numberBetween(1, 100),
        ];
    }
}
