<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolicyAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_protected_routes(): void
    {
        $response = $this->get('/invoices');

        $response->assertRedirect(route('login'));
    }

    public function test_manager_can_access_bookings(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $response = $this->actingAs($manager)->get('/bookings');

        $response->assertStatus(200);
    }

    public function test_photographer_can_access_calendar(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');

        $response = $this->actingAs($photographer)->get('/bookings');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_email_config(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/admin/email-config');

        $response->assertStatus(200);
    }

    public function test_photographer_cannot_cancel_another_photographers_booking(): void
    {
        $photographer1 = User::factory()->create();
        $photographer1->assignRole('crew');

        $photographer2 = User::factory()->create();
        $photographer2->assignRole('crew');

        $booking = \App\Models\Booking::factory()->create(['photographer_id' => $photographer2->id]);

        $response = $this->actingAs($photographer1)->patch(route('bookings.cancel', $booking));
        $response->assertForbidden();
    }

    public function test_manager_cannot_access_admin_routes(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $response = $this->actingAs($manager)->get('/admin/system-health');
        $response->assertForbidden();
    }

    public function test_admin_cannot_access_manager_only_routes(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/manager/requests');
        $response->assertForbidden();
    }

    public function test_crew_cannot_access_manager_dashboard(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');

        $response = $this->actingAs($photographer)->get('/manager/dashboard');
        $response->assertForbidden();
    }

    public function test_crew_cannot_access_invoices_index(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');

        $response = $this->actingAs($photographer)->get(route('invoices.index'));
        $response->assertForbidden();
    }
}

