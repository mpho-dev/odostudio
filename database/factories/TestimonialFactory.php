<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Testimonial>
 */
class TestimonialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_name' => $this->faker->name(),
            'client_initials' => null, // Will be generated
            'event_label' => $this->faker->sentence(3),
            'rating' => $this->faker->numberBetween(4, 5),
            'quote' => $this->faker->paragraph(),
            'is_featured' => $this->faker->boolean(20),
            'order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
