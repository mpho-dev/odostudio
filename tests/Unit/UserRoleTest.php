<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_assigned_admin_role(): void
    {
        $user = User::factory()->create();

        $user->assignRole('admin');

        $this->assertTrue($user->hasRole('admin'));
    }

    public function test_user_can_be_assigned_manager_role(): void
    {
        $user = User::factory()->create();

        $user->assignRole('manager');

        $this->assertTrue($user->hasRole('manager'));
    }

    public function test_user_can_be_assigned_photographer_role(): void
    {
        $user = User::factory()->create();

        $user->assignRole('crew');

        $this->assertTrue($user->hasRole('crew'));
    }

    public function test_user_can_have_multiple_roles(): void
    {
        $user = User::factory()->create();

        $user->assignRole(['manager', 'crew']);

        $this->assertTrue($user->hasRole('manager'));
        $this->assertTrue($user->hasRole('crew'));
    }

    public function test_user_can_remove_role(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manager');

        $user->removeRole('manager');

        $this->assertFalse($user->hasRole('manager'));
    }

    public function test_user_has_relationships(): void
    {
        $user = User::factory()->create();

        $this->assertIsIterable($user->bookings);
        $this->assertIsIterable($user->media);
    }
}
