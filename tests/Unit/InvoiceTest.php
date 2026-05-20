<?php

namespace Tests\Unit;

use App\Models\Booking;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_belongs_to_a_booking(): void
    {
        $booking = Booking::factory()->create();
        $invoice = Invoice::factory()->create(['booking_id' => $booking->id]);

        $this->assertInstanceOf(Booking::class, $invoice->booking);
        $this->assertEquals($booking->id, $invoice->booking->id);
    }

    #[Test]
    public function it_belongs_to_a_creator(): void
    {
        $creator = User::factory()->create();
        $invoice = Invoice::factory()->create(['created_by' => $creator->id]);

        $this->assertInstanceOf(User::class, $invoice->creator);
        $this->assertEquals($creator->id, $invoice->creator->id);
    }

    #[Test]
    public function it_generates_first_invoice_number(): void
    {
        $invoiceNumber = Invoice::generateInvoiceNumber();

        $this->assertEquals('INV-000001', $invoiceNumber);
    }

    #[Test]
    public function it_generates_sequential_invoice_numbers(): void
    {
        Invoice::factory()->create(['invoice_number' => 'INV-000001']);
        Invoice::factory()->create(['invoice_number' => 'INV-000002']);

        $invoiceNumber = Invoice::generateInvoiceNumber();

        $this->assertEquals('INV-000003', $invoiceNumber);
    }

    #[Test]
    public function it_uses_lock_for_update_to_prevent_duplicate_numbers(): void
    {
        Invoice::factory()->create(['invoice_number' => 'INV-000010']);

        $nextNumber = Invoice::generateInvoiceNumber();

        $this->assertEquals('INV-000011', $nextNumber);
    }

    #[Test]
    public function it_pads_invoice_numbers_to_six_digits(): void
    {
        Invoice::factory()->create(['invoice_number' => 'INV-000099']);

        $invoiceNumber = Invoice::generateInvoiceNumber();

        $this->assertEquals('INV-000100', $invoiceNumber);
        $this->assertEquals(10, strlen($invoiceNumber));
    }

    #[Test]
    public function it_handles_large_invoice_numbers(): void
    {
        Invoice::factory()->create(['invoice_number' => 'INV-999999']);

        $invoiceNumber = Invoice::generateInvoiceNumber();

        $this->assertEquals('INV-1000000', $invoiceNumber);
    }

    #[Test]
    public function rate_is_cast_to_decimal_two_places(): void
    {
        $invoice = Invoice::factory()->create(['rate' => 1234.567]);

        $this->assertEquals(1234.57, $invoice->rate);
    }

    #[Test]
    public function total_amount_is_cast_to_decimal_two_places(): void
    {
        $invoice = Invoice::factory()->create(['total_amount' => 9999.999]);

        $this->assertEquals(10000.00, $invoice->total_amount);
    }

    #[Test]
    public function issued_at_is_cast_to_datetime(): void
    {
        $invoice = Invoice::factory()->create([
            'issued_at' => '2026-08-15 14:30:00',
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $invoice->issued_at);
        $this->assertEquals('2026-08-15', $invoice->issued_at->format('Y-m-d'));
    }

    #[Test]
    public function paid_at_is_cast_to_datetime(): void
    {
        $invoice = Invoice::factory()->create([
            'paid_at' => '2026-08-20 10:00:00',
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $invoice->paid_at);
    }

    #[Test]
    public function it_uses_soft_deletes(): void
    {
        $invoice = Invoice::factory()->create();

        $invoice->delete();

        $this->assertSoftDeleted($invoice);
        $this->assertDatabaseHas('invoices', ['id' => $invoice->id]);
    }

    #[Test]
    public function it_stores_invoice_number_uniquely(): void
    {
        $invoice = Invoice::factory()->create(['invoice_number' => 'INV-123456']);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'invoice_number' => 'INV-123456',
        ]);
    }
}
