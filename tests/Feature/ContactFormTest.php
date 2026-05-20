<?php

namespace Tests\Feature;

use App\Jobs\SendBookingRequestEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_shows_validation_errors_for_missing_required_fields(): void
    {
        $response = $this->post(route('contact.store'), []);

        $response->assertSessionHasErrors(['name', 'surname', 'email', 'phone']);
    }

    public function test_submitting_with_event_date_in_the_past_is_not_rejected_on_contact_form()
    {
        // Contact form allows nullable event_date but it only checks if it's a date.
        // The after:today rule is only on the Manager booking creation form.
        // But let's verify it accepts a valid date.

        $response = $this->post(route('contact.store'), [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'event_date' => now()->subDay()->format('Y-m-d\TH:i'), // Past date
            'event_type' => 'Wedding',
            'event_location' => 'Paris',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('booking_requests', [
            'email' => 'john@example.com',
        ]);
    }

    public function test_successful_submission_dispatches_email_job(): void
    {
        Queue::fake();

        $response = $this->post(route('contact.store'), [
            'name' => 'Jane',
            'surname' => 'Smith',
            'email' => 'jane@example.com',
            'phone' => '9876543210',
            'event_date' => now()->addDays(5)->format('Y-m-d\TH:i'),
        ]);

        $response->assertSessionHas('success');

        Queue::assertPushed(SendBookingRequestEmail::class, function ($job) {
            return $job->bookingRequest->email === 'jane@example.com';
        });
    }

    public function test_submitting_with_service_id_is_parsed_correctly(): void
    {
        $service = \App\Models\Service::factory()->create(['title' => 'Test Service']);

        $response = $this->post(route('contact.store'), [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john@test.com',
            'phone' => '1234567890',
            'event_type' => 'service_'.$service->id,
        ]);

        $response->assertSessionHas('success');

        $request = \App\Models\BookingRequest::where('email', 'john@test.com')->first();
        $this->assertEquals($service->id, $request->service_id);
        $this->assertNull($request->investment_tier_id);
        $this->assertNull($request->event_type);
        $this->assertEquals('Test Service', $request->interest_name);
    }

    public function test_submitting_with_investment_tier_id_is_parsed_correctly(): void
    {
        $tier = \App\Models\InvestmentTier::factory()->create(['name' => 'Premium Tier']);

        $response = $this->post(route('contact.store'), [
            'name' => 'Jane',
            'surname' => 'Smith',
            'email' => 'jane@test.com',
            'phone' => '0987654321',
            'event_type' => 'tier_'.$tier->id,
        ]);

        $response->assertSessionHas('success');

        $request = \App\Models\BookingRequest::where('email', 'jane@test.com')->first();
        $this->assertEquals($tier->id, $request->investment_tier_id);
        $this->assertNull($request->service_id);
        $this->assertNull($request->event_type);
        $this->assertEquals('Premium Tier', $request->interest_name);
    }
}
