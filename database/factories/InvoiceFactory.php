<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $booking = Booking::factory()->create();
        $totalAmount = $booking->investmentTier->price ?? 0;

        return [
            'booking_id' => $booking->id,
            'invoice_number' => 'INV-'.str_pad($this->faker->unique()->numberBetween(1, 99999), 6, '0', STR_PAD_LEFT),
            'rate' => $booking->rate,
            'total_amount' => $totalAmount,
            'status' => $this->faker->randomElement(['draft', 'issued', 'paid']),
            'notes' => $this->faker->optional()->paragraph(),
            'pdf_path' => null,
            'issued_at' => $this->faker->optional()->dateTime(),
        ];
    }

    /**
     * Indicate that the invoice is draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'issued_at' => null,
        ]);
    }

    /**
     * Indicate that the invoice is issued.
     */
    public function issued(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'issued',
            'issued_at' => $this->faker->dateTime(),
        ]);
    }

    /**
     * Indicate that the invoice is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'issued_at' => $this->faker->dateTime(),
        ]);
    }
}
