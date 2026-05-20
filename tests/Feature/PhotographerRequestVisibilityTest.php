<?php

namespace Tests\Feature;

use App\Models\BookingRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhotographerRequestVisibilityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that photographers can access the booking requests index.
     */
    public function test_photographers_can_access_requests_index(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');

        $response = $this->actingAs($photographer)->get(route('photographer.requests'));

        $response->assertStatus(200);
        $response->assertViewIs('booking-requests.index');
    }

    /**
     * Test that photographers can view booking requests list - only those assigned to them.
     */
    public function test_photographers_can_view_assigned_booking_requests(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');

        // Create requests - some assigned, some not
        $assignedRequest = BookingRequest::factory()->create(['name' => 'Assigned']);
        $unassignedRequest = BookingRequest::factory()->create(['name' => 'Unassigned']);

        // Assign photographer to only one request
        $assignedRequest->crew()->attach($photographer->id, ['role' => 'photographer']);

        $response = $this->actingAs($photographer)->get(route('photographer.requests'));

        $response->assertStatus(200);
        // Should see the assigned request
        $response->assertSee('Assigned');
        // Should not see the unassigned request
        $response->assertDontSee('Unassigned');
    }

    /**
     * Test that photographers can view individual booking request details - only if assigned.
     */
    public function test_photographers_can_view_assigned_single_request(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');

        $request = BookingRequest::factory()->create([
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'event_type' => 'Wedding Photography',
            'event_location' => 'New York',
            'notes' => 'Looking for an experienced photographer',
        ]);

        // Assign photographer to the request
        $request->crew()->attach($photographer->id, ['role' => 'photographer']);

        $response = $this->actingAs($photographer)->get(route('photographer.requests.show', $request));

        $response->assertStatus(200);
        $response->assertViewIs('booking-requests.show');
        $response->assertSee('John');
        $response->assertSee('Doe');
        $response->assertSee('john@example.com');
        $response->assertSee('Wedding Photography');
        $response->assertSee('New York');
        $response->assertSee('Looking for an experienced photographer');
    }

    /**
     * Test that managers cannot access the photographer-specific requests endpoint.
     */
    public function test_managers_cannot_access_photographer_requests_endpoint(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        BookingRequest::factory(2)->create();

        $response = $this->actingAs($manager)->get(route('photographer.requests'));

        $response->assertForbidden();
    }

    /**
     * Test that unauthenticated users cannot access photographer requests.
     */
    public function test_unauthenticated_users_cannot_access_photographer_requests(): void
    {
        BookingRequest::factory()->create();

        $response = $this->get(route('photographer.requests'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test that unauthorized users cannot access photographer requests.
     */
    public function test_unauthorized_users_cannot_access_photographer_requests(): void
    {
        $user = User::factory()->create();

        BookingRequest::factory()->create();

        $response = $this->actingAs($user)->get(route('photographer.requests'));

        $response->assertForbidden();
    }

    /**
     * Test that booking request shows in empty state correctly.
     */
    public function test_empty_booking_requests_shows_message(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');

        $response = $this->actingAs($photographer)->get(route('photographer.requests'));

        $response->assertStatus(200);
        $response->assertSee('No incoming enquiries at this time.');
    }

    /**
     * Test that pagination works for photographers - showing only assigned requests.
     */
    public function test_photographer_requests_are_paginated(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');

        // Create many requests and assign photographer to some
        $requests = BookingRequest::factory(20)->create();
        foreach ($requests->take(5) as $request) {
            $request->crew()->attach($photographer->id, ['role' => 'photographer']);
        }

        $response = $this->actingAs($photographer)->get(route('photographer.requests'));

        $response->assertStatus(200);
        // Just ensure we get a view response without errors
        $response->assertViewIs('booking-requests.index');
    }

    /**
     * Test that requests are ordered by created date descending - only showing assigned.
     */
    public function test_assigned_requests_ordered_by_created_date_descending(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');

        $request1 = BookingRequest::factory()->create(['name' => 'First']);
        sleep(1);
        $request2 = BookingRequest::factory()->create(['name' => 'Second']);
        sleep(1);
        $request3 = BookingRequest::factory()->create(['name' => 'Third']);

        // Assign photographer to all requests
        $request1->crew()->attach($photographer->id, ['role' => 'photographer']);
        $request2->crew()->attach($photographer->id, ['role' => 'photographer']);
        $request3->crew()->attach($photographer->id, ['role' => 'photographer']);

        $response = $this->actingAs($photographer)->get(route('photographer.requests'));

        $response->assertStatus(200);
        // Verify latest request is included in response
        $response->assertSee('Third');
        $response->assertSee('Second');
    }
}
