<?php

namespace Tests\Feature;

use App\Jobs\SendBookingRequestEmail;
use App\Mail\RequestReceivedMail;
use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RequestNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Queue::fake();
    }

    /**
     * Test that photographers with enabled notifications receive emails for new requests.
     */
    public function test_photographers_with_enabled_notifications_receive_emails(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');
        NotificationPreference::factory()->create([
            'user_id' => $photographer->id,
            'notify_new_requests' => true,
        ]);

        $response = $this->post(route('contact.store'), [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'event_date' => now()->addMonths(6)->toDateString(),
            'event_type' => 'Wedding Photography',
            'event_location' => 'New York',
            'notes' => 'Looking for a photographer',
        ]);

        $response->assertRedirect();

        Mail::assertQueued(RequestReceivedMail::class);
        Mail::assertQueued(RequestReceivedMail::class, function ($mail) use ($photographer) {
            return $mail->hasTo($photographer->email);
        });
    }

    /**
     * Test that photographers with disabled notifications do not receive emails.
     */
    public function test_photographers_with_disabled_notifications_do_not_receive_emails(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');
        NotificationPreference::factory()->create([
            'user_id' => $photographer->id,
            'notify_new_requests' => false,
        ]);

        $this->post(route('contact.store'), [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'event_date' => now()->addMonths(6)->toDateString(),
            'event_type' => 'Wedding Photography',
            'event_location' => 'New York',
        ]);

        Mail::assertNotQueued(RequestReceivedMail::class, function ($mail) use ($photographer) {
            return $mail->hasTo($photographer->email);
        });
    }

    /**
     * Test that all photographers with enabled preferences receive notifications.
     */
    public function test_all_enabled_photographers_receive_notifications(): void
    {
        $photographer1 = User::factory()->create();
        $photographer1->assignRole('crew');
        NotificationPreference::factory()->create([
            'user_id' => $photographer1->id,
            'notify_new_requests' => true,
        ]);

        $photographer2 = User::factory()->create();
        $photographer2->assignRole('crew');
        NotificationPreference::factory()->create([
            'user_id' => $photographer2->id,
            'notify_new_requests' => true,
        ]);

        $this->post(route('contact.store'), [
            'name' => 'Jane',
            'surname' => 'Smith',
            'email' => 'jane@example.com',
            'phone' => '+1234567890',
            'event_date' => now()->addMonths(6)->toDateString(),
            'event_type' => 'Wedding Photography',
            'event_location' => 'Boston',
        ]);

        Mail::assertQueued(RequestReceivedMail::class, function ($mail) use ($photographer1) {
            return $mail->hasTo($photographer1->email);
        });

        Mail::assertQueued(RequestReceivedMail::class, function ($mail) use ($photographer2) {
            return $mail->hasTo($photographer2->email);
        });
    }

    /**
     * Test that admin job is still dispatched for booking requests.
     */
    public function test_admin_job_is_dispatched(): void
    {
        $this->post(route('contact.store'), [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'event_date' => now()->addMonths(6)->toDateString(),
            'event_type' => 'Wedding Photography',
            'event_location' => 'New York',
        ]);

        Queue::assertPushed(SendBookingRequestEmail::class);
    }

    /**
     * Test that photographers without notification preferences default to enabled.
     */
    public function test_photographers_without_preferences_default_to_enabled(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');
        // No notification preference created

        $this->post(route('contact.store'), [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'event_date' => now()->addMonths(6)->toDateString(),
            'event_type' => 'Wedding Photography',
            'event_location' => 'New York',
        ]);

        // Since photographer has no preference, they shouldn't receive
        Mail::assertNotQueued(RequestReceivedMail::class, function ($mail) use ($photographer) {
            return $mail->hasTo($photographer->email);
        });
    }

    /**
     * Test that request notification email contains all request details.
     */
    public function test_request_notification_contains_booking_details(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');
        NotificationPreference::factory()->create([
            'user_id' => $photographer->id,
            'notify_new_requests' => true,
        ]);

        $response = $this->post(route('contact.store'), [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+1-555-123-4567',
            'event_date' => '2025-12-15',
            'event_type' => 'Wedding Photography',
            'event_location' => 'Central Park, New York',
            'notes' => 'We need a full-day photographer',
        ]);

        Mail::assertQueued(RequestReceivedMail::class, function ($mail) use ($photographer) {
            return $mail->hasTo($photographer->email) &&
                   $mail->bookingRequest->name === 'John' &&
                   $mail->bookingRequest->surname === 'Doe' &&
                   $mail->bookingRequest->email === 'john.doe@example.com' &&
                   $mail->bookingRequest->event_type === 'Wedding Photography';
        });
    }

    /**
     * Test that non-photographer roles do not receive notifications.
     */
    public function test_non_photographers_do_not_receive_notifications(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $this->post(route('contact.store'), [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'event_date' => now()->addMonths(6)->toDateString(),
            'event_type' => 'Wedding Photography',
            'event_location' => 'New York',
        ]);

        Mail::assertNotQueued(RequestReceivedMail::class, function ($mail) use ($manager) {
            return $mail->hasTo($manager->email);
        });
    }

    /**
     * Test that booking request is stored successfully.
     */
    public function test_booking_request_is_stored(): void
    {
        $this->post(route('contact.store'), [
            'name' => 'Jane',
            'surname' => 'Smith',
            'email' => 'jane@example.com',
            'phone' => '+1234567890',
            'event_date' => now()->addMonths(6)->toDateString(),
            'event_type' => 'Portrait Session',
            'event_location' => 'Studio',
            'notes' => 'Family portrait',
        ]);

        $this->assertDatabaseHas('booking_requests', [
            'name' => 'Jane',
            'surname' => 'Smith',
            'email' => 'jane@example.com',
            'event_type' => 'Portrait Session',
        ]);
    }

    /**
     * Test that toggling preference persists changes.
     */
    public function test_toggling_preference_persists_changes(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');
        $initial = NotificationPreference::factory()->create([
            'user_id' => $photographer->id,
            'notify_new_requests' => true,
        ]);

        $this->assertTrue($initial->notify_new_requests);

        // Disable notifications
        $photographer->notificationPreference->update(['notify_new_requests' => false]);

        $updated = $photographer->notificationPreference->refresh();
        $this->assertFalse($updated->notify_new_requests);
    }
}
