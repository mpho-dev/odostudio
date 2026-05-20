<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTalentManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an admin can view the user management page.
     */
    public function test_admin_can_view_user_management_page(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.users.index'));

        $response->assertStatus(200);
    }

    /**
     * Test that an admin can view the create user form.
     */
    public function test_admin_can_view_create_user_form(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.users.create'));

        $response->assertStatus(200);
        $response->assertSee('Recruit Specialist');
    }

    /**
     * Test that an admin can create a photographer crew member.
     */
    public function test_admin_can_create_photographer_crew_member(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'John Photographer',
                'email' => 'john@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'roles' => ['crew'],
                'crew_specialty' => 'photographer',
            ]);

        $response->assertRedirect(route('admin.users.index'));

        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('photographer', $user->crew_specialty);
        $this->assertTrue($user->hasRole('crew'));
        $this->assertTrue($user->must_change_password);
    }

    /**
     * Test that an admin can create a videographer crew member.
     */
    public function test_admin_can_create_videographer_crew_member(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Jane Videographer',
                'email' => 'jane@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'roles' => ['crew'],
                'crew_specialty' => 'videographer',
            ]);

        $response->assertRedirect(route('admin.users.index'));

        $user = User::where('email', 'jane@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('videographer', $user->crew_specialty);
        $this->assertTrue($user->hasRole('crew'));
        $this->assertTrue($user->must_change_password);
    }

    /**
     * Test that an admin can create a dual-skilled crew member.
     */
    public function test_admin_can_create_dual_skill_crew_member(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Alex Specialist',
                'email' => 'alex@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'roles' => ['crew'],
                'crew_specialty' => 'both',
            ]);

        $response->assertRedirect(route('admin.users.index'));

        $user = User::where('email', 'alex@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('both', $user->crew_specialty);
        $this->assertTrue($user->hasRole('crew'));
    }

    /**
     * Test that an admin can create a manager without crew specialty.
     */
    public function test_admin_can_create_manager_without_crew_specialty(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Manager Name',
                'email' => 'manager@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'roles' => ['manager'],
            ]);

        $response->assertRedirect(route('admin.users.index'));

        $user = User::where('email', 'manager@example.com')->first();
        $this->assertNotNull($user);
        $this->assertNull($user->crew_specialty);
        $this->assertTrue($user->hasRole('manager'));
        $this->assertTrue($user->must_change_password);
    }

    /**
     * Test that an admin can edit a crew member's specialty.
     */
    public function test_admin_can_edit_crew_member_specialty(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $photographer = User::factory()->photographer()->create();
        $photographer->assignRole('crew');

        $response = $this
            ->actingAs($admin)
            ->put(route('admin.users.update', $photographer), [
                'name' => $photographer->name,
                'email' => $photographer->email,
                'roles' => ['crew'],
                'crew_specialty' => 'both',
            ]);

        $response->assertRedirect(route('admin.users.index'));

        $photographer->refresh();
        $this->assertEquals('both', $photographer->crew_specialty);
    }

    /**
     * Test that crew specialty is removed when role is changed.
     */
    public function test_crew_specialty_removed_when_role_changed(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $photographer = User::factory()->photographer()->create();
        $photographer->assignRole('crew');

        $this->assertEquals('photographer', $photographer->crew_specialty);

        $response = $this
            ->actingAs($admin)
            ->put(route('admin.users.update', $photographer), [
                'name' => $photographer->name,
                'email' => $photographer->email,
                'roles' => ['manager'],
                'crew_specialty' => null,
            ]);

        $response->assertRedirect(route('admin.users.index'));

        $photographer->refresh();
        $this->assertNull($photographer->crew_specialty);
        $this->assertFalse($photographer->hasRole('crew'));
    }

    /**
     * Test that the user management index shows crew specialty.
     */
    public function test_user_index_shows_crew_specialty(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $photographer = User::factory()->photographer()->create();
        $photographer->assignRole('crew');

        $videographer = User::factory()->videographer()->create();
        $videographer->assignRole('crew');

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSee('Crew: photographer');
        $response->assertSee('Crew: videographer');
    }

    /**
     * Test that a non-admin cannot access user management.
     */
    public function test_non_admin_cannot_access_user_management(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('admin.users.index'));

        $response->assertStatus(403);
    }

    /**
     * Test that an admin cannot delete their own account.
     */
    public function test_admin_cannot_delete_own_account(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this
            ->actingAs($admin)
            ->delete(route('admin.users.destroy', $admin));

        $response->assertSessionHasErrors();
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    /**
     * Test that an admin can delete another user.
     */
    public function test_admin_can_delete_another_user(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $photographer = User::factory()->create();
        $photographer->assignRole('crew');

        $response = $this
            ->actingAs($admin)
            ->delete(route('admin.users.destroy', $photographer));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertSoftDeleted('users', ['id' => $photographer->id]);
    }

    /**
     * Test that creating a crew member without required fields fails.
     */
    public function test_crew_member_creation_validation(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => '',
                'email' => 'invalid',
                'password' => 'short',
                'password_confirmation' => 'nomatch',
                'roles' => [],
            ]);

        $response->assertSessionHasErrors(['name', 'email', 'password', 'roles']);
    }

    /**
     * Test that invalid crew specialty is rejected.
     */
    public function test_invalid_crew_specialty_rejected(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'roles' => ['crew'],
                'crew_specialty' => 'invalid_specialty',
            ]);

        $response->assertSessionHasErrors('crew_specialty');
    }
}
