<?php

namespace Tests\Feature;

use App\Models\BookingRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingRequestShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_cannot_view_photographer_booking_request_route(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $bookingRequest = BookingRequest::factory()->create();

        $response = $this->actingAs($manager)
            ->get(route('photographer.requests.show', $bookingRequest));

        $response->assertForbidden(); // Route has role:crew middleware
    }

    public function test_crew_member_can_view_a_booking_request(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');

        $bookingRequest = BookingRequest::factory()->create();

        $response = $this->actingAs($photographer)
            ->get(route('photographer.requests.show', $bookingRequest));

        $response->assertStatus(200);
    }

    public function test_guest_cannot_view_booking_request(): void
    {
        $bookingRequest = BookingRequest::factory()->create();

        $response = $this->get(route('photographer.requests.show', $bookingRequest));

        $response->assertRedirect(route('login'));
    }

    public function test_manager_can_view_booking_requests_list(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        BookingRequest::factory(5)->create();

        $response = $this->actingAs($manager)
            ->get(route('requests.index')); // Correct manager route

        $response->assertStatus(200);
        $response->assertViewHas('requests'); // Usually the variable is 'requests' in BookingController::indexRequests
    }

    public function test_crew_can_view_booking_requests_list(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');

        $response = $this->actingAs($photographer)
            ->get(route('photographer.requests'));

        $response->assertStatus(200);
        $response->assertViewHas('bookingRequests');
    }
}

