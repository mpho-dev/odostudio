<?php

namespace Tests\Feature;

use App\Models\InvestmentTier;
use App\Models\ProcessStep;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminContentManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Services Tests
     */
    public function test_admin_can_create_service(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post(route('services.store'), [
            'title' => 'Photography',
            'description' => 'Professional photography',
            'starting_price' => 1000,
            'features' => ['High res', 'Editing included'],
        ]);

        $response->assertRedirect(route('services.index'));
        $this->assertDatabaseHas('services', [
            'title' => 'Photography',
            'starting_price' => 1000,
        ]);
    }

    public function test_admin_can_update_service(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $service = Service::factory()->create();

        $response = $this->actingAs($admin)->put(route('services.update', $service), [
            'title' => 'Updated Service',
            'description' => 'Updated description',
            'starting_price' => 2000,
            'features' => ['Updated feature'],
        ]);

        $response->assertRedirect(route('services.index'));
        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'title' => 'Updated Service',
        ]);
    }

    public function test_admin_can_delete_service(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $service = Service::factory()->create();

        $response = $this->actingAs($admin)->delete(route('services.destroy', $service));

        $response->assertRedirect(route('services.index'));
        $this->assertDatabaseMissing('services', ['id' => $service->id]);
    }

    public function test_service_validation_requires_title(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post(route('services.store'), [
            'description' => 'Description',
            'starting_price' => 100,
        ]);

        $response->assertSessionHasErrors('title');
    }

    /**
     * Investment Tiers Tests
     */
    public function test_admin_can_view_investment_tiers_index(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('investment-tiers.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.investment-tiers.index');
    }

    public function test_admin_can_create_investment_tier(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post(route('investment-tiers.store'), [
            'tier_label' => 'Standard',
            'name' => 'Standard Package',
            'price' => 1500.00,
            'features' => ['Feature 1', 'Feature 2'],
            'is_featured' => true,
        ]);

        $response->assertRedirect(route('investment-tiers.index'));
        $this->assertDatabaseHas('investment_tiers', [
            'name' => 'Standard Package',
            'price' => 1500.00,
            'is_featured' => true,
        ]);
    }

    public function test_admin_can_update_investment_tier(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $tier = InvestmentTier::factory()->create();

        $response = $this->actingAs($admin)->put(route('investment-tiers.update', $tier), [
            'tier_label' => 'Updated Label',
            'name' => 'Updated Name',
            'price' => 2000.00,
            'features' => ['Updated Feature'],
        ]);

        $response->assertRedirect(route('investment-tiers.index'));
        $this->assertDatabaseHas('investment_tiers', [
            'id' => $tier->id,
            'name' => 'Updated Name',
            'price' => 2000.00,
        ]);
    }

    public function test_admin_can_delete_investment_tier(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $tier = InvestmentTier::factory()->create();

        $response = $this->actingAs($admin)->delete(route('investment-tiers.destroy', $tier));

        $response->assertRedirect(route('investment-tiers.index'));
        $this->assertDatabaseMissing('investment_tiers', ['id' => $tier->id]);
    }

    public function test_non_admin_cannot_manage_investment_tiers(): void
    {
        $user = User::factory()->create(); // No role or non-admin role

        $response = $this->actingAs($user)->get(route('investment-tiers.index'));
        $response->assertForbidden();

        $response = $this->actingAs($user)->post(route('investment-tiers.store'), []);
        $response->assertForbidden();
    }

    public function test_investment_tier_validation_requires_name_and_price(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post(route('investment-tiers.store'), [
            'tier_label' => 'Label',
        ]);

        $response->assertSessionHasErrors(['name', 'price']);
    }

    /**
     * Process Steps Tests
     */
    public function test_admin_can_create_process_step(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post(route('process-steps.store'), [
            'step_number' => 1,
            'title' => 'Initial Consultation',
            'description' => 'Meet and greet',
        ]);

        $response->assertRedirect(route('process-steps.index'));
        $this->assertDatabaseHas('process_steps', [
            'step_number' => 1,
            'title' => 'Initial Consultation',
        ]);
    }

    public function test_admin_can_update_process_step(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $step = ProcessStep::factory()->create();

        $response = $this->actingAs($admin)->put(route('process-steps.update', $step), [
            'step_number' => 2,
            'title' => 'Updated Step',
            'description' => 'Updated Description',
        ]);

        $response->assertRedirect(route('process-steps.index'));
        $this->assertDatabaseHas('process_steps', [
            'id' => $step->id,
            'title' => 'Updated Step',
        ]);
    }

    public function test_admin_can_delete_process_step(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $step = ProcessStep::factory()->create();

        $response = $this->actingAs($admin)->delete(route('process-steps.destroy', $step));

        $response->assertRedirect(route('process-steps.index'));
        // Soft deleted
        $this->assertSoftDeleted('process_steps', ['id' => $step->id]);
    }

    /**
     * Testimonials Tests
     */
    public function test_admin_can_create_testimonial(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post(route('testimonials.store'), [
            'client_name' => 'John Doe',
            'rating' => 5,
            'quote' => 'Great service!',
        ]);

        $response->assertRedirect(route('testimonials.index'));
        $this->assertDatabaseHas('testimonials', [
            'client_name' => 'John Doe',
            'quote' => 'Great service!',
        ]);
    }

    public function test_admin_can_update_testimonial(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $testimonial = Testimonial::factory()->create();

        $response = $this->actingAs($admin)->put(route('testimonials.update', $testimonial), [
            'client_name' => 'Jane Doe',
            'rating' => 4,
            'quote' => 'Good service',
        ]);

        $response->assertRedirect(route('testimonials.index'));
        $this->assertDatabaseHas('testimonials', [
            'id' => $testimonial->id,
            'client_name' => 'Jane Doe',
            'rating' => 4,
        ]);
    }

    public function test_admin_can_delete_testimonial(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $testimonial = Testimonial::factory()->create();

        $response = $this->actingAs($admin)->delete(route('testimonials.destroy', $testimonial));

        $response->assertRedirect(route('testimonials.index'));
        $this->assertDatabaseMissing('testimonials', ['id' => $testimonial->id]);
    }
}
