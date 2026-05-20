<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_users(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_create_user_with_role(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // Role 'manager' might already be seeded or created in setUp, but let's ensure it exists
        if (! Role::where('name', 'manager')->exists()) {
            Role::create(['name' => 'manager']);
        }

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'New Manager',
            'email' => 'manager@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'roles' => ['manager'],
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'manager@example.com']);
        $user = User::where('email', 'manager@example.com')->first();
        $this->assertTrue($user->hasRole('manager'));
    }

    public function test_admin_can_update_user_role(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $user = User::factory()->create();
        $user->assignRole('crew');

        if (! Role::where('name', 'manager')->exists()) {
            Role::create(['name' => 'manager']);
        }

        $response = $this->actingAs($admin)->put(route('admin.users.update', $user), [
            'name' => $user->name,
            'email' => $user->email,
            'roles' => ['manager'],
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertTrue($user->refresh()->hasRole('manager'));
        $this->assertFalse($user->hasRole('crew'));
    }

    public function test_admin_cannot_delete_self(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin));

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        // Check it wasn't deleted (soft deleted or otherwise)
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_can_delete_other_user(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $user = User::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $user));

        $response->assertRedirect(route('admin.users.index'));
        // Soft deleted
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }
}
