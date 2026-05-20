<?php

namespace Tests\Feature;

use App\Models\EmailConfiguration;
use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailConfigurationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        
        // Seed basic templates for testing
        EmailTemplate::create([
            'slug' => 'booking_request_admin',
            'name' => 'Booking Request (Admin)',
            'subject' => 'New Request from {name} {surname}',
            'body' => 'Hi {name}, new request for {event_type} on {event_date}',
            'placeholders' => ['{name}' => 'First Name', '{event_type}' => 'Event Type'],
        ]);
        
        EmailTemplate::create([
            'slug' => 'visitor_request_confirmation',
            'name' => 'Visitor Confirmation',
            'subject' => 'Thank you for your inquiry',
            'body' => 'Hi {name}, we received your request for {service_type}.',
            'placeholders' => ['{name}' => 'First Name', '{service_type}' => 'Service Type'],
        ]);
    }

    /**
     * Test that admins can access email preview endpoint.
     */
    public function test_admin_can_preview_email(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->getJson(route('admin.email-config.preview', 'booking_request_admin'));

        $response->assertStatus(200);
        $response->assertJsonStructure(['subject', 'body', 'html']);
        $this->assertStringContainsString('New Request from Thabo Mokoena', $response['subject']);
        $this->assertStringContainsString('new request for Cinematic Brand Film', $response['body']);
    }

    /**
     * Test that email preview replaces all placeholders.
     */
    public function test_email_preview_replaces_all_placeholders(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->getJson(route('admin.email-config.preview', 'booking_request_admin'));

        $response->assertStatus(200);
        $body = $response['body'];

        // Ensure placeholders are replaced with sample data
        $this->assertStringNotContainsString('{name}', $body);
        $this->assertStringNotContainsString('{event_type}', $body);
        $this->assertStringContainsString('Thabo', $body);
        $this->assertStringContainsString('Cinematic Brand Film', $body);
    }

    /**
     * Test that admins can send test email.
     */
    public function test_admin_can_send_test_email(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post(
            route('admin.email-config.test'),
            [
                'test_email' => 'test@example.com',
                'template_slug' => 'booking_request_admin'
            ]
        );

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    /**
     * Test that email config can be updated via form.
     */
    public function test_admin_can_update_email_config(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post(
            route('admin.email-config.update'),
            [
                'booking_request_email_from' => 'new@example.com',
                'booking_request_email_to' => 'newto@example.com',
                'templates' => [
                    'booking_request_admin' => [
                        'subject' => 'Updated Admin Subject',
                        'body' => '<p>Updated Admin Body</p>',
                    ],
                    'visitor_request_confirmation' => [
                        'subject' => 'Updated Visitor Subject',
                        'body' => '<p>Updated Visitor Body</p>',
                    ],
                ]
            ]
        );

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('new@example.com', EmailConfiguration::getConfig('booking_request_email_from'));
        
        $template = EmailTemplate::where('slug', 'booking_request_admin')->first();
        $this->assertEquals('Updated Admin Subject', $template->subject);
    }
}
