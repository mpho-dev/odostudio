<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrewAssignmentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a crew member can be marked as a photographer.
     */
    public function test_crew_member_can_be_photographer(): void
    {
        $photographer = User::factory()->photographer()->create();

        $this->assertEquals('photographer', $photographer->crew_specialty);
    }

    /**
     * Test that a crew member can be marked as a videographer.
     */
    public function test_crew_member_can_be_videographer(): void
    {
        $videographer = User::factory()->videographer()->create();

        $this->assertEquals('videographer', $videographer->crew_specialty);
    }

    /**
     * Test that a crew member can be marked as both photographer and videographer.
     */
    public function test_crew_member_can_be_photographer_and_videographer(): void
    {
        $crewMember = User::factory()->videographerAndPhotographer()->create();

        $this->assertEquals('both', $crewMember->crew_specialty);
    }

    /**
     * Test that a booking can have multiple crew members.
     */
    public function test_booking_can_have_multiple_crew_members(): void
    {
        $booking = Booking::factory()->create();
        $photographer = User::factory()->photographer()->create();
        $videographer = User::factory()->videographer()->create();

        $booking->crew()->attach($photographer->id, ['role' => 'photographer']);
        $booking->crew()->attach($videographer->id, ['role' => 'videographer']);

        $this->assertCount(2, $booking->crew);
        $this->assertTrue($booking->crew->contains($photographer));
        $this->assertTrue($booking->crew->contains($videographer));
    }

    /**
     * Test that a booking can retrieve only photographers.
     */
    public function test_booking_can_retrieve_photographers(): void
    {
        $booking = Booking::factory()->create();
        $photographer1 = User::factory()->photographer()->create();
        $photographer2 = User::factory()->photographer()->create();
        $videographer = User::factory()->videographer()->create();

        $booking->crew()->attach($photographer1->id, ['role' => 'photographer']);
        $booking->crew()->attach($photographer2->id, ['role' => 'photographer']);
        $booking->crew()->attach($videographer->id, ['role' => 'videographer']);

        $photographers = $booking->photographers;

        $this->assertCount(2, $photographers);
        $this->assertTrue($photographers->contains($photographer1));
        $this->assertTrue($photographers->contains($photographer2));
        $this->assertFalse($photographers->contains($videographer));
    }

    /**
     * Test that a booking can retrieve only videographers.
     */
    public function test_booking_can_retrieve_videographers(): void
    {
        $booking = Booking::factory()->create();
        $photographer = User::factory()->photographer()->create();
        $videographer1 = User::factory()->videographer()->create();
        $videographer2 = User::factory()->videographer()->create();

        $booking->crew()->attach($photographer->id, ['role' => 'photographer']);
        $booking->crew()->attach($videographer1->id, ['role' => 'videographer']);
        $booking->crew()->attach($videographer2->id, ['role' => 'videographer']);

        $videographers = $booking->videographers;

        $this->assertCount(2, $videographers);
        $this->assertTrue($videographers->contains($videographer1));
        $this->assertTrue($videographers->contains($videographer2));
        $this->assertFalse($videographers->contains($photographer));
    }

    /**
     * Test that the same user can have both photographer and videographer roles on one booking.
     */
    public function test_same_user_can_have_both_roles_on_booking(): void
    {
        $booking = Booking::factory()->create();
        $crewMember = User::factory()->videographerAndPhotographer()->create();

        $booking->crew()->attach($crewMember->id, ['role' => 'photographer']);
        $booking->crew()->attach($crewMember->id, ['role' => 'videographer']);

        $this->assertCount(2, $booking->crew);
        $this->assertTrue($booking->photographers->contains($crewMember));
        $this->assertTrue($booking->videographers->contains($crewMember));
    }

    /**
     * Test that a crew member can be retrieved through their crew bookings.
     */
    public function test_crew_member_can_retrieve_their_bookings(): void
    {
        $crew = User::factory()->photographer()->create();
        $booking1 = Booking::factory()->create();
        $booking2 = Booking::factory()->create();

        $booking1->crew()->attach($crew->id, ['role' => 'photographer']);
        $booking2->crew()->attach($crew->id, ['role' => 'photographer']);

        $crewBookings = $crew->crewBookings;

        $this->assertCount(2, $crewBookings);
        $this->assertTrue($crewBookings->contains($booking1));
        $this->assertTrue($crewBookings->contains($booking2));
    }

    /**
     * Test that crew assignment includes role information in pivot.
     */
    public function test_crew_assignment_includes_role_pivot(): void
    {
        $booking = Booking::factory()->create();
        $photographer = User::factory()->photographer()->create();

        $booking->crew()->attach($photographer->id, ['role' => 'photographer']);

        $crewMember = $booking->crew->first();
        $this->assertEquals('photographer', $crewMember->pivot->role);
    }

    /**
     * Test that removing a crew member works correctly.
     */
    public function test_removing_crew_member_from_booking(): void
    {
        $booking = Booking::factory()->create();
        $photographer = User::factory()->photographer()->create();
        $videographer = User::factory()->videographer()->create();

        $booking->crew()->attach($photographer->id, ['role' => 'photographer']);
        $booking->crew()->attach($videographer->id, ['role' => 'videographer']);

        $this->assertCount(2, $booking->crew);

        // Detach and reload to verify
        $booking->crew()->detach($photographer->id);
        $booking->refresh();

        $this->assertCount(1, $booking->crew);
        $this->assertTrue($booking->crew->contains($videographer));
        $this->assertFalse($booking->crew->contains($photographer));
    }

    /**
     * Test that using factory with crew members works correctly.
     */
    public function test_booking_factory_with_crew_members(): void
    {
        $photographer = User::factory()->photographer()->create();
        $videographer = User::factory()->videographer()->create();

        $booking = Booking::factory()
            ->withCrew([
                ['user' => $photographer, 'role' => 'photographer'],
                ['user' => $videographer, 'role' => 'videographer'],
            ])
            ->create();

        $this->assertCount(2, $booking->crew);
        $this->assertTrue($booking->photographers->contains($photographer));
        $this->assertTrue($booking->videographers->contains($videographer));
    }

    /**
     * Test that dual-role crew members can be assigned as either photographer or videographer.
     */
    public function test_dual_role_crew_can_be_assigned_as_either_role(): void
    {
        $bookingRequest = \App\Models\BookingRequest::factory()->create();
        $dualRoleCrew = User::factory()->videographerAndPhotographer()->create();
        $dualRoleCrew->assignRole('crew');
        $manager = User::factory()->create();
        $manager->assignRole('manager');
        
        // Test assignment as photographer
        $response = $this->actingAs($manager)
            ->post(route('requests.crew.assign', $bookingRequest), [
                'user_id' => $dualRoleCrew->id,
                'role' => 'photographer',
            ]);
            
        $response->assertRedirect();
        $this->assertDatabaseHas('booking_request_crew', [
            'booking_request_id' => $bookingRequest->id,
            'user_id' => $dualRoleCrew->id,
            'role' => 'photographer',
        ]);
        
        // Remove assignment and test as videographer
        $bookingRequest->crew()->detach($dualRoleCrew->id);
        
        $response = $this->actingAs($manager)
            ->post(route('requests.crew.assign', $bookingRequest), [
                'user_id' => $dualRoleCrew->id,
                'role' => 'videographer',
            ]);
            
        $response->assertRedirect();
        $this->assertDatabaseHas('booking_request_crew', [
            'booking_request_id' => $bookingRequest->id,
            'user_id' => $dualRoleCrew->id,
            'role' => 'videographer',
        ]);
    }

    /**
     * Test that single-role crew members cannot be assigned incompatible roles.
     */
    public function test_single_role_crew_cannot_be_assigned_incompatible_role(): void
    {
        $bookingRequest = \App\Models\BookingRequest::factory()->create();
        $photographer = User::factory()->photographer()->create();
        $photographer->assignRole('crew');
        $manager = User::factory()->create();
        $manager->assignRole('manager');
        
        // Try to assign photographer as videographer (should fail)
        $response = $this->actingAs($manager)
            ->post(route('requests.crew.assign', $bookingRequest), [
                'user_id' => $photographer->id,
                'role' => 'videographer',
            ]);
            
        $response->assertSessionHasErrors('user_id');
        $this->assertDatabaseMissing('booking_request_crew', [
            'booking_request_id' => $bookingRequest->id,
            'user_id' => $photographer->id,
            'role' => 'videographer',
        ]);
    }

    /**
     * Test multiple photographers on same booking.
     */
    public function test_multiple_photographers_on_same_booking(): void
    {
        $photographer1 = User::factory()->photographer()->create();
        $photographer2 = User::factory()->photographer()->create();
        $photographer3 = User::factory()->photographer()->create();

        $booking = Booking::factory()
            ->withCrew([
                ['user' => $photographer1, 'role' => 'photographer'],
                ['user' => $photographer2, 'role' => 'photographer'],
                ['user' => $photographer3, 'role' => 'photographer'],
            ])
            ->create();

        $this->assertCount(3, $booking->photographers);
        $this->assertCount(3, $booking->crew);
    }
}
