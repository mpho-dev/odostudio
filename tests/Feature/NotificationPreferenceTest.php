<?php

namespace Tests\Feature;

use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationPreferenceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that photographers can access notification preferences page.
     */
    public function test_photographers_can_access_preferences_page(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');
        NotificationPreference::factory()->create(['user_id' => $photographer->id]);

        $response = $this->actingAs($photographer)->get(route('photographer.preferences'));

        $response->assertStatus(200);
        $response->assertViewIs('photographer.notification-preferences');
    }

    /**
     * Test that notification preferences are created if not exist.
     */
    public function test_preferences_created_if_not_exist(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');

        $response = $this->actingAs($photographer)->get(route('photographer.preferences'));

        $response->assertStatus(200);
        $this->assertTrue(
            NotificationPreference::where('user_id', $photographer->id)->exists()
        );
    }

    /**
     * Test that photographers can toggle notification preferences.
     */
    public function test_photographers_can_update_preferences(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');
        NotificationPreference::factory()->create([
            'user_id' => $photographer->id,
            'notify_new_requests' => true,
        ]);

        $response = $this->actingAs($photographer)->patch(
            route('photographer.preferences.update'),
            ['notify_new_requests' => false]
        );

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertFalse(
            $photographer->notificationPreference->refresh()->notify_new_requests
        );
    }

    /**
     * Test that photographers can re-enable notifications.
     */
    public function test_photographers_can_reenable_notifications(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');
        NotificationPreference::factory()->create([
            'user_id' => $photographer->id,
            'notify_new_requests' => false,
        ]);

        $response = $this->actingAs($photographer)->patch(
            route('photographer.preferences.update'),
            ['notify_new_requests' => true]
        );

        $response->assertRedirect();
        $this->assertTrue(
            $photographer->notificationPreference->refresh()->notify_new_requests
        );
    }

    /**
     * Test that unauthenticated users cannot access preferences.
     */
    public function test_unauthenticated_users_cannot_access_preferences(): void
    {
        $response = $this->get(route('photographer.preferences'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test that non-photographers cannot access preferences.
     */
    public function test_non_photographers_cannot_access_preferences(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manager');

        $response = $this->actingAs($user)->get(route('photographer.preferences'));

        $response->assertForbidden();
    }

    /**
     * Test that preference form shows correct state.
     */
    public function test_preference_form_shows_current_state(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');
        NotificationPreference::factory()->create([
            'user_id' => $photographer->id,
            'notify_new_requests' => true,
        ]);

        $response = $this->actingAs($photographer)->get(route('photographer.preferences'));

        $response->assertStatus(200);
        $response->assertSee('checked');
    }

    /**
     * Test that preference update validates input.
     */
    public function test_preference_update_validates_boolean(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');
        NotificationPreference::factory()->create(['user_id' => $photographer->id]);

        $response = $this->actingAs($photographer)->patch(
            route('photographer.preferences.update'),
            ['notify_new_requests' => 'invalid']
        );

        $response->assertSessionHasErrors('notify_new_requests');
    }

    /**
     * Test that updated at timestamp is recorded.
     */
    public function test_preference_updated_at_is_recorded(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');
        $preference = NotificationPreference::factory()->create([
            'user_id' => $photographer->id,
            'notify_new_requests' => true,
        ]);

        $originalUpdatedAt = $preference->updated_at;

        sleep(1); // Ensure time difference

        $this->actingAs($photographer)->patch(
            route('photographer.preferences.update'),
            ['notify_new_requests' => false]
        );

        $updatedPreference = $photographer->notificationPreference->refresh();
        $this->assertGreaterThan($originalUpdatedAt, $updatedPreference->updated_at);
    }
}
