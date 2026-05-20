<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_profile_edit_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertStatus(200);
    }

    public function test_user_can_update_their_name_and_email(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);

        $response->assertRedirect(route('profile.edit'));

        $user->refresh();
        $this->assertEquals('New Name', $user->name);
        $this->assertEquals('new@example.com', $user->email);
    }

    public function test_user_can_delete_their_own_account(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete(route('profile.destroy'), [
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function test_guest_cannot_view_profile_edit_page(): void
    {
        $response = $this->get(route('profile.edit'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_update_profile(): void
    {
        $response = $this->patch(route('profile.update'), [
            'name' => 'Hacker',
            'email' => 'hack@example.com',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_profile_update_validates_email_uniqueness(): void
    {
        $existingUser = User::factory()->create(['email' => 'taken@example.com']);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => $user->name,
            'email' => 'taken@example.com',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_user_can_update_email_to_their_own_existing_email(): void
    {
        $user = User::factory()->create(['email' => 'same@example.com']);

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'Same Name',
            'email' => 'same@example.com',
        ]);

        $response->assertSessionHasNoErrors();
    }
}
