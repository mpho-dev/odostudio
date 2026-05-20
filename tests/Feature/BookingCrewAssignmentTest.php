<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\BookingRequest;
use App\Models\InvestmentTier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingCrewAssignmentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a manager can view the create booking form with photographers and videographers.
     */
    public function test_manager_can_view_create_booking_form(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $photographer = User::factory()->photographer()->create();
        $photographer->assignRole('crew');

        $videographer = User::factory()->videographer()->create();
        $videographer->assignRole('crew');

        $bookingRequest = BookingRequest::factory()->create();

        $response = $this
            ->actingAs($manager)
            ->get(route('bookings.create', $bookingRequest));

        $response->assertStatus(200);
        $response->assertViewHas('photographers');
        $response->assertViewHas('videographers');
    }

    /**
     * Test that a manager can create a booking with only photographers.
     */
    public function test_manager_can_create_booking_with_photographers(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $photographer1 = User::factory()->photographer()->create();
        $photographer1->assignRole('crew');

        $photographer2 = User::factory()->photographer()->create();
        $photographer2->assignRole('crew');

        $investmentTier = InvestmentTier::factory()->create();
        $bookingRequest = BookingRequest::factory()->create();

        $response = $this
            ->actingAs($manager)
            ->post(route('bookings.store', $bookingRequest), [
                'crew_id' => [
                    'photographers' => [$photographer1->id, $photographer2->id],
                    'videographers' => [],
                ],
                'investment_tier_id' => $investmentTier->id,
                'event_date' => now()->addDay()->format('Y-m-d\TH:i'),
                'location' => 'Studio A',
            ]);

        $response->assertRedirect(route('bookings.index'));

        $booking = Booking::where('booking_request_id', $bookingRequest->id)->first();
        $this->assertNotNull($booking);
        $this->assertCount(2, $booking->photographers);
        $this->assertTrue($booking->photographers->contains($photographer1));
        $this->assertTrue($booking->photographers->contains($photographer2));
    }

    /**
     * Test that a manager can create a booking with only videographers.
     */
    public function test_manager_can_create_booking_with_videographers(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $videographer1 = User::factory()->videographer()->create();
        $videographer1->assignRole('crew');

        $videographer2 = User::factory()->videographer()->create();
        $videographer2->assignRole('crew');

        $investmentTier = InvestmentTier::factory()->create();
        $bookingRequest = BookingRequest::factory()->create();

        $response = $this
            ->actingAs($manager)
            ->post(route('bookings.store', $bookingRequest), [
                'crew_id' => [
                    'photographers' => [],
                    'videographers' => [$videographer1->id, $videographer2->id],
                ],
                'investment_tier_id' => $investmentTier->id,
                'event_date' => now()->addDay()->format('Y-m-d\TH:i'),
                'location' => 'Studio B',
            ]);

        $response->assertRedirect(route('bookings.index'));

        $booking = Booking::where('booking_request_id', $bookingRequest->id)->first();
        $this->assertNotNull($booking);
        $this->assertCount(2, $booking->videographers);
        $this->assertTrue($booking->videographers->contains($videographer1));
        $this->assertTrue($booking->videographers->contains($videographer2));
    }

    /**
     * Test that a manager can create a booking with both photographers and videographers.
     */
    public function test_manager_can_create_booking_with_mixed_crew(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $photographer = User::factory()->photographer()->create();
        $photographer->assignRole('crew');

        $videographer = User::factory()->videographer()->create();
        $videographer->assignRole('crew');

        $investmentTier = InvestmentTier::factory()->create();
        $bookingRequest = BookingRequest::factory()->create();

        $response = $this
            ->actingAs($manager)
            ->post(route('bookings.store', $bookingRequest), [
                'crew_id' => [
                    'photographers' => [$photographer->id],
                    'videographers' => [$videographer->id],
                ],
                'investment_tier_id' => $investmentTier->id,
                'event_date' => now()->addDay()->format('Y-m-d\TH:i'),
                'location' => 'Outdoor Location',
            ]);

        $response->assertRedirect(route('bookings.index'));

        $booking = Booking::where('booking_request_id', $bookingRequest->id)->first();
        $this->assertNotNull($booking);
        $this->assertCount(1, $booking->photographers);
        $this->assertCount(1, $booking->videographers);
        $this->assertTrue($booking->photographers->contains($photographer));
        $this->assertTrue($booking->videographers->contains($videographer));
    }

    /**
     * Test that booking creation fails when no crew members are assigned.
     */
    public function test_booking_creation_fails_without_crew(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $investmentTier = InvestmentTier::factory()->create();
        $bookingRequest = BookingRequest::factory()->create();

        $response = $this
            ->actingAs($manager)
            ->post(route('bookings.store', $bookingRequest), [
                'crew_id' => [
                    'photographers' => [],
                    'videographers' => [],
                ],
                'investment_tier_id' => $investmentTier->id,
                'event_date' => now()->addDay()->format('Y-m-d\TH:i'),
                'location' => 'Studio',
            ]);

        $response->assertSessionHasErrors('crew');
        $this->assertCount(0, Booking::where('booking_request_id', $bookingRequest->id)->get());
    }

    /**
     * Test that the booking index shows all crew members with their roles.
     */
    public function test_booking_index_shows_all_crew(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $photographer = User::factory()->photographer()->create();
        $photographer->assignRole('crew');

        $videographer = User::factory()->videographer()->create();
        $videographer->assignRole('crew');

        $booking = Booking::factory()
            ->withCrew([
                ['user' => $photographer, 'role' => 'photographer'],
                ['user' => $videographer, 'role' => 'videographer'],
            ])
            ->create();

        $response = $this
            ->actingAs($manager)
            ->get(route('bookings.index'));

        $response->assertStatus(200);
        $response->assertSee($photographer->name);
        $response->assertSee($videographer->name);
        $response->assertSee('photographer');
        $response->assertSee('videographer');
    }

    /**
     * Test that a crew member can see only their assigned bookings.
     */
    public function test_crew_member_sees_only_their_bookings(): void
    {
        $photographer1 = User::factory()->photographer()->create();
        $photographer1->assignRole('crew');

        $photographer2 = User::factory()->photographer()->create();
        $photographer2->assignRole('crew');

        $booking1 = Booking::factory()->create();
        $booking1->crew()->attach($photographer1->id, ['role' => 'photographer']);

        $booking2 = Booking::factory()->create();
        $booking2->crew()->attach($photographer2->id, ['role' => 'photographer']);

        $unrelatedBooking = Booking::factory()->create();

        $response = $this
            ->actingAs($photographer1)
            ->get(route('bookings.index'));

        $response->assertStatus(200);
        $response->assertSee($booking1->bookingRequest->name);
        $response->assertDontSee($booking2->bookingRequest->name);
        $response->assertDontSee($unrelatedBooking->bookingRequest->name);
    }

    /**
     * Test that the booking request status is updated to confirmed when booking is created.
     */
    public function test_booking_request_status_updated(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $photographer = User::factory()->photographer()->create();
        $photographer->assignRole('crew');

        $investmentTier = InvestmentTier::factory()->create();
        $bookingRequest = BookingRequest::factory()->create(['status' => 'pending']);

        $this->assertEquals('pending', $bookingRequest->status);

        $this
            ->actingAs($manager)
            ->post(route('bookings.store', $bookingRequest), [
                'crew_id' => [
                    'photographers' => [$photographer->id],
                    'videographers' => [],
                ],
                'investment_tier_id' => $investmentTier->id,
                'event_date' => now()->addDay()->format('Y-m-d\TH:i'),
                'location' => 'Studio',
            ]);

        $bookingRequest->refresh();
        $this->assertEquals('confirmed', $bookingRequest->status);
    }

    /**
     * Test that a crew member with 'both' specialty can be selected as photographer or videographer.
     */
    public function test_both_specialty_crew(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $bothRoles = User::factory()->videographerAndPhotographer()->create();
        $bothRoles->assignRole('crew');

        $investmentTier = InvestmentTier::factory()->create();
        $bookingRequest = BookingRequest::factory()->create();

        $response = $this
            ->actingAs($manager)
            ->post(route('bookings.store', $bookingRequest), [
                'crew_id' => [
                    'photographers' => [$bothRoles->id],
                    'videographers' => [],
                ],
                'investment_tier_id' => $investmentTier->id,
                'event_date' => now()->addDay()->format('Y-m-d\TH:i'),
                'location' => 'Studio',
            ]);

        $response->assertRedirect(route('bookings.index'));

        $booking = Booking::where('booking_request_id', $bookingRequest->id)->first();
        $this->assertNotNull($booking);
        $this->assertTrue($booking->photographers->contains($bothRoles));
    }
}
