<?php

namespace Tests\Unit;

use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NotificationPreferenceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_belongs_to_a_user(): void
    {
        $user = User::factory()->create();
        $preference = NotificationPreference::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $preference->user);
        $this->assertEquals($user->id, $preference->user->id);
    }

    #[Test]
    public function it_has_default_true_for_notify_new_requests(): void
    {
        $preference = NotificationPreference::factory()->create();

        $this->assertTrue($preference->notify_new_requests);
    }

    #[Test]
    public function it_can_set_notify_new_requests_to_false(): void
    {
        $preference = NotificationPreference::factory()->create([
            'notify_new_requests' => false,
        ]);

        $this->assertFalse($preference->notify_new_requests);
    }

    #[Test]
    public function notification_flags_are_cast_to_boolean(): void
    {
        $preference = NotificationPreference::factory()->create([
            'notify_new_requests' => 1,
        ]);

        $this->assertIsBool($preference->notify_new_requests);
        $this->assertTrue($preference->notify_new_requests);
    }

    #[Test]
    public function it_can_update_notification_preferences(): void
    {
        $preference = NotificationPreference::factory()->create([
            'notify_new_requests' => true,
        ]);

        $preference->update(['notify_new_requests' => false]);

        $this->assertFalse($preference->fresh()->notify_new_requests);
    }

    #[Test]
    public function user_can_have_only_one_notification_preference(): void
    {
        $user = User::factory()->create();
        $preference1 = NotificationPreference::factory()->create(['user_id' => $user->id]);

        $this->assertEquals($preference1->id, $user->notificationPreference->id);
    }
}
