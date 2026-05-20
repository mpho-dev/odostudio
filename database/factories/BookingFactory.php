<?php

namespace Database\Factories;

use App\Models\BookingRequest;
use App\Models\InvestmentTier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'booking_request_id' => BookingRequest::factory(),
            'investment_tier_id' => InvestmentTier::factory()->create()->id,
            'photographer_id' => User::factory(),
            'event_date' => $this->faker->dateTimeBetween('+1 day', '+6 months'),
            'location' => $this->faker->city().', '.$this->faker->state(),
            'rate' => null,
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),
        ];
    }

    /**
     * Indicate that the booking is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the booking is confirmed.
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
        ]);
    }

    /**
     * Indicate that the booking is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    /**
     * Indicate that the booking is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }

    /**
     * Assign crew members to the booking after creation.
     * Usage: Booking::factory()->withCrew([
     *     ['user' => $user, 'role' => 'photographer'],
     *     ['user' => $user2, 'role' => 'videographer'],
     * ])->create()
     *
     * @param  array<int, array{user: User, role: string}>  $crewMembers
     */
    public function withCrew(array $crewMembers): static
    {
        return $this->afterCreating(function ($booking) use ($crewMembers) {
            foreach ($crewMembers as $crew) {
                $booking->crew()->attach($crew['user']->id, ['role' => $crew['role']]);
            }
        });
    }
}
