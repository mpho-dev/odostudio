<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\BookingRequest;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagerDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_view_dashboard_with_stats(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        BookingRequest::factory(2)->pending()->create();
        Booking::factory(3)->create(['status' => 'confirmed']);
        Invoice::factory(4)->create(['status' => 'draft']);
        Invoice::factory(1)->create(['status' => 'paid', 'total_amount' => 500.00]);
        Invoice::factory(1)->create(['status' => 'paid', 'total_amount' => 250.00]);

        $response = $this->actingAs($manager)->get(route('manager.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('pendingRequests');
        $response->assertViewHas('activeBookings');
        $response->assertViewHas('draftInvoices');
        $response->assertViewHas('totalRevenue');
    }
}
