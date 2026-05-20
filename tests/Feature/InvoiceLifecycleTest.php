<?php

namespace Tests\Feature;

use App\Mail\InvoiceSentMail;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InvoiceLifecycleTest extends TestCase
{
    use RefreshDatabase;

    protected User $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->manager = User::factory()->create();
        $this->manager->assignRole('manager');
    }

    public function test_manager_can_mark_invoice_as_sent_and_email_is_queued(): void
    {
        Mail::fake();

        $booking = Booking::factory()->create();
        $invoice = Invoice::factory()->create([
            'booking_id' => $booking->id,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->manager)
            ->patch(route('invoices.sent', $invoice));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $invoice->refresh();
        $this->assertEquals('sent', $invoice->status);
        $this->assertNotNull($invoice->issued_at);

        Mail::assertQueued(InvoiceSentMail::class, function ($mail) use ($invoice) {
            return $mail->invoice->id === $invoice->id;
        });
    }



    public function test_photographer_cannot_mark_invoice_as_sent(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');

        $booking = Booking::factory()->create();
        $invoice = Invoice::factory()->create([
            'booking_id' => $booking->id,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($photographer)
            ->patch(route('invoices.sent', $invoice));

        $response->assertForbidden();
    }

    public function test_admin_can_view_all_invoices(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $manager1 = User::factory()->create();
        $manager1->assignRole('manager');

        $manager2 = User::factory()->create();
        $manager2->assignRole('manager');

        Invoice::factory(3)->create(['created_by' => $manager1->id]);
        Invoice::factory(2)->create(['created_by' => $manager2->id]);

        $response = $this->actingAs($admin)->get(route('invoices.index'));

        $response->assertStatus(200);
        $this->assertEquals(5, $response->viewData('invoices')->total());
    }

    public function test_crew_cannot_view_another_photographers_invoice(): void
    {
        $photographer1 = User::factory()->create();
        $photographer1->assignRole('crew');

        $photographer2 = User::factory()->create();
        $photographer2->assignRole('crew');

        $booking = Booking::factory()->create(['photographer_id' => $photographer2->id]);
        $invoice = Invoice::factory()->create(['booking_id' => $booking->id]);

        $response = $this->actingAs($photographer1)
            ->get(route('invoices.show', $invoice));

        $response->assertForbidden();
    }

    public function test_crew_can_view_their_own_booking_invoice(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');

        $booking = Booking::factory()->create(['photographer_id' => $photographer->id]);
        $invoice = Invoice::factory()->create(['booking_id' => $booking->id]);

        $response = $this->actingAs($photographer)
            ->get(route('invoices.show', $invoice));

        $response->assertStatus(200);
    }
}
