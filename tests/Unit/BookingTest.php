<?php

namespace Tests\Unit;

use App\Models\Booking;
use App\Models\BookingRequest;
use App\Models\InvestmentTier;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_belongs_to_a_booking_request(): void
    {
        $bookingRequest = BookingRequest::factory()->create();
        $booking = Booking::factory()->create(['booking_request_id' => $bookingRequest->id]);

        $this->assertInstanceOf(BookingRequest::class, $booking->bookingRequest);
        $this->assertEquals($bookingRequest->id, $booking->bookingRequest->id);
    }

    #[Test]
    public function it_belongs_to_an_investment_tier(): void
    {
        $tier = InvestmentTier::factory()->create();
        $booking = Booking::factory()->create(['investment_tier_id' => $tier->id]);

        $this->assertInstanceOf(InvestmentTier::class, $booking->investmentTier);
        $this->assertEquals($tier->id, $booking->investmentTier->id);
    }

    #[Test]
    public function it_belongs_to_a_photographer(): void
    {
        $photographer = User::factory()->create();
        $booking = Booking::factory()->create(['photographer_id' => $photographer->id]);

        $this->assertInstanceOf(User::class, $booking->photographer);
        $this->assertEquals($photographer->id, $booking->photographer->id);
    }

    #[Test]
    public function it_has_one_invoice(): void
    {
        $booking = Booking::factory()->create();
        $invoice = Invoice::factory()->create(['booking_id' => $booking->id]);

        $this->assertInstanceOf(Invoice::class, $booking->invoice);
        $this->assertEquals($invoice->id, $booking->invoice->id);
    }

    #[Test]
    public function it_belongs_to_a_creator(): void
    {
        $creator = User::factory()->create();
        $booking = Booking::factory()->create(['created_by' => $creator->id]);

        $this->assertInstanceOf(User::class, $booking->creator);
        $this->assertEquals($creator->id, $booking->creator->id);
    }

    #[Test]
    public function it_can_have_many_crew_members(): void
    {
        $booking = Booking::factory()->create();
        $photographer1 = User::factory()->create();
        $photographer2 = User::factory()->create();

        $booking->crew()->attach($photographer1->id, ['role' => 'photographer']);
        $booking->crew()->attach($photographer2->id, ['role' => 'videographer']);

        $this->assertCount(2, $booking->crew);
        $this->assertTrue($booking->crew->contains($photographer1));
        $this->assertTrue($booking->crew->contains($photographer2));
    }

    #[Test]
    public function it_can_get_only_photographers(): void
    {
        $booking = Booking::factory()->create();
        $photographer = User::factory()->create();
        $videographer = User::factory()->create();

        $booking->crew()->attach($photographer->id, ['role' => 'photographer']);
        $booking->crew()->attach($videographer->id, ['role' => 'videographer']);

        $photographers = $booking->photographers;

        $this->assertCount(1, $photographers);
        $this->assertTrue($photographers->contains($photographer));
        $this->assertFalse($photographers->contains($videographer));
    }

    #[Test]
    public function it_can_get_only_videographers(): void
    {
        $booking = Booking::factory()->create();
        $photographer = User::factory()->create();
        $videographer = User::factory()->create();

        $booking->crew()->attach($photographer->id, ['role' => 'photographer']);
        $booking->crew()->attach($videographer->id, ['role' => 'videographer']);

        $videographers = $booking->videographers;

        $this->assertCount(1, $videographers);
        $this->assertTrue($videographers->contains($videographer));
        $this->assertFalse($videographers->contains($photographer));
    }

    #[Test]
    public function total_amount_returns_investment_tier_price_when_present(): void
    {
        $tier = InvestmentTier::factory()->create(['price' => 1500.00]);
        $booking = Booking::factory()->create([
            'investment_tier_id' => $tier->id,
            'rate' => 500.00,
        ]);

        $this->assertEquals(1500.00, $booking->total_amount);
    }

    #[Test]
    public function total_amount_returns_rate_when_no_investment_tier(): void
    {
        $booking = Booking::factory()->create([
            'investment_tier_id' => null,
            'rate' => 800.00,
        ]);

        $this->assertEquals(800.00, $booking->total_amount);
    }

    #[Test]
    public function total_amount_returns_zero_when_no_tier_or_rate(): void
    {
        $booking = Booking::factory()->create([
            'investment_tier_id' => null,
            'rate' => null,
        ]);

        $this->assertEquals(0, $booking->total_amount);
    }

    #[Test]
    public function it_can_be_cancelled(): void
    {
        $booking = Booking::factory()->create(['status' => 'pending']);

        $result = $booking->cancel();

        $this->assertTrue($result);
        $this->assertEquals('cancelled', $booking->fresh()->status);
    }

    #[Test]
    public function it_can_be_restored_from_cancellation(): void
    {
        $booking = Booking::factory()->create(['status' => 'cancelled']);

        $result = $booking->restoreFromCancellation();

        $this->assertTrue($result);
        $this->assertEquals('pending', $booking->fresh()->status);
    }

    #[Test]
    public function it_uses_soft_deletes(): void
    {
        $booking = Booking::factory()->create();

        $booking->delete();

        $this->assertSoftDeleted($booking);
        $this->assertDatabaseHas('bookings', ['id' => $booking->id]);
    }

    #[Test]
    public function event_date_is_cast_to_datetime(): void
    {
        $booking = Booking::factory()->create([
            'event_date' => '2026-06-15 14:30:00',
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $booking->event_date);
        $this->assertEquals('2026-06-15', $booking->event_date->format('Y-m-d'));
    }

    #[Test]
    public function rate_is_cast_to_decimal(): void
    {
        $booking = Booking::factory()->create(['rate' => 1234.5678]);

        $this->assertEquals(1234.57, $booking->rate);
    }
}
