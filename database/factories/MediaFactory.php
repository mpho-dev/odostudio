<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $mediaType = $this->faker->randomElement(['image', 'video', 'gif']);

        $mimeTypes = [
            'image' => ['image/jpeg', 'image/png', 'image/webp'],
            'video' => ['video/mp4', 'video/webm', 'video/quicktime'],
            'gif' => ['image/gif'],
        ];

        return [
            'file_path' => 'media/'.$this->faker->slug(3).'.'.$this->faker->randomElement(['jpg', 'png', 'webp', 'mp4', 'webm', 'gif']),
            'file_name' => $this->faker->word().'.'.$this->faker->randomElement(['jpg', 'png', 'webp', 'mp4', 'webm', 'gif']),
            'file_type' => $this->faker->randomElement($mimeTypes[$mediaType]),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'uploaded_by' => User::factory(),
            'media_type' => $mediaType,
        ];
    }

    /**
     * Indicate that the media is an image.
     */
    public function image(): static
    {
        return $this->state(fn (array $attributes) => [
            'media_type' => 'image',
            'file_type' => $this->faker->randomElement(['image/jpeg', 'image/png', 'image/webp']),
        ]);
    }

    /**
     * Indicate that the media is a video.
     */
    public function video(): static
    {
        return $this->state(fn (array $attributes) => [
            'media_type' => 'video',
            'file_type' => $this->faker->randomElement(['video/mp4', 'video/webm', 'video/quicktime']),
        ]);
    }

    /**
     * Indicate that the media is a gif.
     */
    public function gif(): static
    {
        return $this->state(fn (array $attributes) => [
            'media_type' => 'gif',
            'file_type' => 'image/gif',
        ]);
    }
}
