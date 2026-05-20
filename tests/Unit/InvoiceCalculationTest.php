<?php

namespace Tests\Unit;

use App\Models\Booking;
use App\Models\InvestmentTier;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceCalculationTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoice_total_amount_from_tier(): void
    {
        $tierPrice = 550.00;
        $tier = InvestmentTier::factory()->create(['price' => $tierPrice]);
        $booking = Booking::factory()->create(['investment_tier_id' => $tier->id]);

        $invoice = Invoice::factory()->create([
            'booking_id' => $booking->id,
            'total_amount' => $tierPrice,
        ]);

        $this->assertEquals($tierPrice, $invoice->total_amount);
    }

    public function test_invoice_status_defaults_to_draft(): void
    {
        $invoice = Invoice::factory()->draft()->create();

        $this->assertEquals('draft', $invoice->status);
    }

    public function test_invoice_can_be_issued(): void
    {
        $invoice = Invoice::factory()->issued()->create();

        $this->assertEquals('issued', $invoice->status);
    }

    public function test_invoice_can_be_paid(): void
    {
        $invoice = Invoice::factory()->paid()->create();

        $this->assertEquals('paid', $invoice->status);
    }
}
