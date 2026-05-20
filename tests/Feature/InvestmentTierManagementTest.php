<?php

namespace Tests\Feature;

use App\Models\InvestmentTier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvestmentTierManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    public function test_admin_can_create_investment_tier(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('investment-tiers.store'), [
                'tier_label' => 'Starter',
                'name' => 'Essential Package',
                'price' => 15000,
                'description' => 'Perfect for individuals',
                'features' => ['Feature 1', 'Feature 2'],
                'order' => 1,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('investment_tiers', [
            'name' => 'Essential Package',
            'price' => 15000,
        ]);
    }

    public function test_admin_can_update_investment_tier(): void
    {
        $tier = InvestmentTier::factory()->create([
            'name' => 'Original Name',
            'price' => 10000,
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('investment-tiers.update', $tier), [
                'tier_label' => 'Pro',
                'name' => 'Updated Name',
                'price' => 25000,
                'description' => 'Updated description',
                'features' => ['Feature 1'],
                'order' => 1,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('investment_tiers', [
            'id' => $tier->id,
            'name' => 'Updated Name',
            'price' => 25000,
        ]);
    }

    public function test_admin_can_delete_investment_tier(): void
    {
        $tier = InvestmentTier::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('investment-tiers.destroy', $tier));

        $response->assertRedirect();
        $this->assertModelMissing($tier);
    }

    public function test_investment_tier_requires_valid_price(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('investment-tiers.store'), [
                'name' => 'Test Tier',
                'price' => -5000, // Invalid: negative price
                'tier_label' => 'Test',
            ]);

        $response->assertSessionHasErrors();
    }

    public function test_featured_tier_badge_can_be_set(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('investment-tiers.store'), [
                'tier_label' => 'Popular',
                'name' => 'Popular Package',
                'price' => 20000,
                'is_featured' => true,
                'badge_label' => 'Most Popular',
                'features' => ['Feature 1'],
                'order' => 1,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('investment_tiers', [
            'name' => 'Popular Package',
            'is_featured' => true,
            'badge_label' => 'Most Popular',
        ]);
    }

    public function test_non_admin_cannot_manage_investment_tiers(): void
    {
        $user = User::factory()->create();
        $user->assignRole('crew');

        $response = $this->actingAs($user)
            ->post(route('investment-tiers.store'), [
                'name' => 'Unauthorized Tier',
                'price' => 10000,
            ]);

        $response->assertStatus(403);
    }

    public function test_investment_tiers_display_on_homepage(): void
    {
        InvestmentTier::factory()->count(3)->create();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Investment tiers', false);
    }
}
