<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\InvestmentTier;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_create_invoice_for_booking(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $tier = InvestmentTier::factory()->create(['price' => 600.00]);
        $booking = Booking::factory()->create(['investment_tier_id' => $tier->id]);

        $response = $this->actingAs($manager)->post(
            route('invoices.store', $booking),
            [
                'notes' => 'Performance service',
            ]
        );

        $this->assertDatabaseHas('invoices', [
            'booking_id' => $booking->id,
            'total_amount' => 600.00,
            'created_by' => $manager->id,
        ]);

        $invoice = Invoice::where('booking_id', $booking->id)->first();
        $this->assertNotNull($invoice->issued_at);
    }

    public function test_manager_sees_only_own_invoices(): void
    {
        $manager1 = User::factory()->create();
        $manager1->assignRole('manager');

        $manager2 = User::factory()->create();
        $manager2->assignRole('manager');

        Invoice::factory(2)->create(['created_by' => $manager1->id]);
        Invoice::factory(3)->create(['created_by' => $manager2->id]);

        $response = $this->actingAs($manager1)->get(route('invoices.index'));
        $response->assertStatus(200);
        $this->assertEquals(2, $response->viewData('invoices')->count());
    }

    public function test_invoice_number_is_auto_generated(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $tier = InvestmentTier::factory()->create();
        $booking = Booking::factory()->create(['investment_tier_id' => $tier->id]);

        Invoice::factory()->create([
            'invoice_number' => 'INV-000001',
        ]);

        $this->actingAs($manager)->post(
            route('invoices.store', $booking),
            []
        );

        $invoice = Invoice::where('booking_id', $booking->id)->first();
        $this->assertNotNull($invoice->invoice_number);
        $this->assertStringStartsWith('INV-', $invoice->invoice_number);
    }

    public function test_invoice_status_defaults_to_draft(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $tier = InvestmentTier::factory()->create();
        $booking = Booking::factory()->create(['investment_tier_id' => $tier->id]);

        $this->actingAs($manager)->post(
            route('invoices.store', $booking),
            [
                'notes' => 'Performance service',
            ]
        );

        $invoice = Invoice::where('booking_id', $booking->id)->first();
        $this->assertEquals('draft', $invoice->status);
    }

    public function test_manager_can_download_invoice_pdf(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $booking = Booking::factory()->create();
        $invoice = Invoice::factory()->create(['booking_id' => $booking->id]);

        // Just test that the route is accessible
        $response = $this->actingAs($manager)
            ->get('/invoices/'.$invoice->id.'/pdf');

        $response->assertStatus(200);
    }

    public function test_photographer_cannot_create_invoices(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');

        $tier = InvestmentTier::factory()->create();
        $booking = Booking::factory()->create(['investment_tier_id' => $tier->id]);

        $response = $this->actingAs($photographer)->post(
            route('invoices.store', $booking),
            []
        );

        $response->assertForbidden();
    }

    public function test_invoice_total_amount_calculated_correctly(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $tier = InvestmentTier::factory()->create(['price' => 188.75]);
        $booking = Booking::factory()->create(['investment_tier_id' => $tier->id]);

        $this->actingAs($manager)->post(
            route('invoices.store', $booking),
            []
        );

        $invoice = Invoice::where('booking_id', $booking->id)->first();
        $this->assertEquals(188.75, $invoice->total_amount);
    }
}
