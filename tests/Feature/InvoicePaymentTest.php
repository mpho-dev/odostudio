<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Invoice;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoicePaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_manager_can_mark_invoice_as_paid()
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $booking = Booking::factory()->create();
        $invoice = Invoice::factory()->create([
            'booking_id' => $booking->id,
            'status' => 'sent',
            'paid_at' => null,
        ]);

        $response = $this->actingAs($manager)->patch(route('invoices.pay', $invoice));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $invoice->refresh();

        $this->assertEquals('paid', $invoice->status);
        $this->assertNotNull($invoice->paid_at);
    }

    public function test_photographer_cannot_mark_invoice_as_paid()
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');

        $invoice = Invoice::factory()->create([
            'status' => 'issued',
        ]);

        $response = $this->actingAs($photographer)->patch(route('invoices.pay', $invoice));

        $response->assertForbidden();
    }
}
