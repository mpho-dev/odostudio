<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    public function test_admin_can_view_services(): void
    {
        Service::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('services.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_create_service(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('services.store'), [
                'title' => 'Wedding Photography',
                'description' => 'Cinematic wedding photography',
                'icon' => '📷',
                'starting_price' => 5000,
                'order' => 1,
                'features' => ['Full day coverage', 'Edited photos', 'Album'],
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('services', [
            'title' => 'Wedding Photography',
            'starting_price' => 5000,
        ]);
    }

    public function test_admin_can_update_service(): void
    {
        $service = Service::factory()->create([
            'title' => 'Original Title',
            'starting_price' => 1000,
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('services.update', $service), [
                'title' => 'Updated Title',
                'description' => 'Updated description',
                'icon' => '🎬',
                'starting_price' => 2000,
                'order' => 2,
                'features' => ['Feature 1'],
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'title' => 'Updated Title',
            'starting_price' => 2000,
        ]);
    }

    public function test_admin_can_delete_service(): void
    {
        $service = Service::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('services.destroy', $service));

        $response->assertRedirect();
        $this->assertModelMissing($service);
    }

    public function test_non_admin_cannot_create_service(): void
    {
        $user = User::factory()->create();
        $user->assignRole('crew');

        $response = $this->actingAs($user)
            ->post(route('services.store'), [
                'title' => 'Unauthorized Service',
                'description' => 'Should not be created',
                'starting_price' => 1000,
            ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('services', ['title' => 'Unauthorized Service']);
    }

    public function test_service_requires_valid_data(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('services.store'), [
                'title' => '', // Empty title
                'starting_price' => -100, // Invalid price
            ]);

        $response->assertSessionHasErrors();
    }
}
