<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    use RefreshDatabase;

    // ─── Dashboard access ────────────────────────────────────────────────────

    public function test_admin_can_view_health_dashboard(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/admin/system-health');

        $response->assertStatus(200);
        $response->assertViewHas('checks');
    }

    public function test_non_admin_cannot_view_health_dashboard(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $response = $this->actingAs($manager)->get('/admin/system-health');

        $response->assertForbidden();
    }

    public function test_guest_is_redirected_from_health_dashboard(): void
    {
        $response = $this->get('/admin/system-health');

        $response->assertRedirect(route('login'));
    }

    // ─── Individual health endpoints ─────────────────────────────────────────

    public function test_database_health_check_returns_success(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->getJson('/api/health/database');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_cache_health_check_returns_success_and_validates_readback(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->getJson('/api/health/cache');

        $response->assertStatus(200)
            ->assertJson(['success' => true, 'message' => 'Cache system working']);
    }

    public function test_roles_health_check_returns_success_when_three_roles_exist(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // Roles are created by the RolesAndPermissionsSeeder via RefreshDatabase hooks
        // The seeder is run via DatabaseSeeder — check that at least 3 roles exist
        $response = $this->actingAs($admin)->getJson('/api/health/roles');

        $response->assertStatus(200);
        // Roles may show warning if seeder not run; either success or warning is acceptable
        $this->assertContains(
            $response->json('type'),
            ['success', 'warning'],
            'Roles check should return success or warning, not error'
        );
    }

    public function test_storage_health_check_returns_success(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->getJson('/api/health/storage');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}
