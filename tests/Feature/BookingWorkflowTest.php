<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\BookingRequest;
use App\Models\InvestmentTier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class BookingWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitor_can_submit_booking_request(): void
    {
        $response = $this->post('/contact', [
            'name' => 'Jane',
            'surname' => 'Smith',
            'email' => 'jane@example.com',
            'phone' => '555-1234',
            'notes' => 'Event on Saturday',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('booking_requests', [
            'email' => 'jane@example.com',
            'status' => 'pending',
        ]);
    }

    public function test_contact_form_is_rate_limited(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/contact', [
                'name' => "Rate Limit $i",
                'surname' => 'Test',
                'email' => 'test@example.com',
                'phone' => '123456789',
            ])->assertRedirect();
        }

        // 6th request within 1 minute should return 429
        $response = $this->post('/contact', [
            'name' => 'Rate Limit 6',
            'surname' => 'Test',
        ]);

        $response->assertStatus(429);
    }

    public function test_manager_can_view_pending_requests(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        BookingRequest::factory(3)->pending()->create();

        $response = $this->actingAs($manager)->get('/manager/requests');

        $response->assertStatus(200);
        $response->assertViewHas('requests');
    }

    public function test_manager_can_create_booking_from_request(): void
    {
        Mail::fake();

        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $photographer = User::factory()->create();
        $photographer->assignRole('crew');

        $tier = InvestmentTier::factory()->create();
        $request = BookingRequest::factory()->pending()->create();

        $response = $this->actingAs($manager)->post(
            route('bookings.store', $request),
            [
                'crew_id' => [
                    'photographers' => [$photographer->id],
                    'videographers' => [],
                ],
                'investment_tier_id' => $tier->id,
                'event_date' => now()->addDays(10)->format('Y-m-d\TH:i'),
                'location' => 'Central Hall',
            ]
        );

        $response->assertRedirect(route('bookings.index'));

        $this->assertDatabaseHas('bookings', [
            'booking_request_id' => $request->id,
            'photographer_id' => $photographer->id,
            'investment_tier_id' => $tier->id,
            'created_by' => $manager->id,
        ]);

        Mail::assertQueued(\App\Mail\BookingConfirmedMail::class);
    }

    public function test_manager_cannot_create_booking_with_past_date(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $photographer = User::factory()->create();
        $photographer->assignRole('crew');

        $tier = InvestmentTier::factory()->create();
        $request = BookingRequest::factory()->pending()->create();

        $response = $this->actingAs($manager)->post(
            route('bookings.store', $request),
            [
                'crew_id' => [
                    'photographers' => [$photographer->id],
                    'videographers' => [],
                ],
                'investment_tier_id' => $tier->id,
                'event_date' => now()->subDay()->format('Y-m-d\TH:i'),
                'location' => 'Central Hall',
            ]
        );

        $response->assertSessionHasErrors(['event_date']);
    }

    public function test_manager_can_view_all_bookings(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        Booking::factory(5)->create();

        $response = $this->actingAs($manager)->get('/bookings');

        $response->assertStatus(200);
    }

    public function test_photographer_can_view_bookings(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');

        Booking::factory(3)->create(['photographer_id' => $photographer->id]);

        $response = $this->actingAs($photographer)->get('/bookings');

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_bookings(): void
    {
        $response = $this->get('/bookings');

        $response->assertRedirect(route('login'));
    }

    public function test_manager_can_cancel_booking(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $booking = Booking::factory()->create();

        $response = $this->actingAs($manager)->patch(route('bookings.cancel', $booking));

        $response->assertRedirect();

        // Assert it's NOT soft deleted but status is cancelled
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled',
            'deleted_at' => null,
        ]);

        $booking->refresh();
        $this->assertEquals('cancelled', $booking->status);
    }

    public function test_manager_can_confirm_booking(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $booking = Booking::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($manager)->patch(route('bookings.confirm', $booking));

        $response->assertRedirect();

        $booking->refresh();
        $this->assertEquals('confirmed', $booking->status);
    }

    public function test_manager_can_complete_booking(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $booking = Booking::factory()->create(['status' => 'confirmed']);

        $response = $this->actingAs($manager)->patch(route('bookings.complete', $booking));

        $response->assertRedirect();

        $booking->refresh();
        $this->assertEquals('completed', $booking->status);
    }

    public function test_crew_cannot_confirm_booking_they_do_not_own(): void
    {
        $photographer1 = User::factory()->create();
        $photographer1->assignRole('crew');

        $photographer2 = User::factory()->create();
        $photographer2->assignRole('crew');

        $booking = Booking::factory()->create(['photographer_id' => $photographer2->id]);

        $response = $this->actingAs($photographer1)->patch(route('bookings.confirm', $booking));

        $response->assertForbidden();
    }

    public function test_admin_can_restore_soft_deleted_booking(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $booking = Booking::factory()->create();
        $booking->delete();

        $this->assertSoftDeleted('bookings', ['id' => $booking->id]);

        $response = $this->actingAs($admin)->patch(route('bookings.restore', $booking->id));

        $response->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'deleted_at' => null,
            'status' => 'pending',
        ]);
    }

    public function test_admin_can_restore_cancelled_booking(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $booking = Booking::factory()->create(['status' => 'cancelled']);

        $response = $this->actingAs($admin)
            ->post(route('bookings.restore-cancellation', $booking));

        $response->assertRedirect();

        $booking->refresh();
        $this->assertNotEquals('cancelled', $booking->status);
    }

    public function test_restore_from_cancellation_fails_for_non_cancelled_booking(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $booking = Booking::factory()->create(['status' => 'confirmed']);

        $response = $this->actingAs($admin)
            ->post(route('bookings.restore-cancellation', $booking));

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $booking->refresh();
        $this->assertEquals('confirmed', $booking->status);
    }

    public function test_admin_can_force_delete_booking(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $booking = Booking::factory()->create();
        $booking->delete();

        $response = $this->actingAs($admin)
            ->delete(route('bookings.forceDelete', $booking->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }

    public function test_manager_cannot_force_delete_booking(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $booking = Booking::factory()->create();
        $booking->delete();

        $response = $this->actingAs($manager)
            ->delete(route('bookings.forceDelete', $booking->id));

        $response->assertForbidden();
    }
}
