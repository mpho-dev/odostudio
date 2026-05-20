<?php

namespace Tests\Feature;

use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonialManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    public function test_admin_can_create_testimonial(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('testimonials.store'), [
                'client_name' => 'John Smith',
                'client_initials' => 'JS',
                'quote' => 'Amazing photography and videography services!',
                'rating' => 5,
                'order' => 1,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('testimonials', [
            'client_name' => 'John Smith',
            'rating' => 5,
        ]);
    }

    public function test_admin_can_update_testimonial(): void
    {
        $testimonial = Testimonial::factory()->create([
            'client_name' => 'Original Name',
            'rating' => 4,
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('testimonials.update', $testimonial), [
                'client_name' => 'Updated Name',
                'client_initials' => 'UN',
                'quote' => 'Updated quote',
                'rating' => 5,
                'order' => 1,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('testimonials', [
            'id' => $testimonial->id,
            'client_name' => 'Updated Name',
            'rating' => 5,
        ]);
    }

    public function test_admin_can_delete_testimonial(): void
    {
        $testimonial = Testimonial::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('testimonials.destroy', $testimonial));

        $response->assertRedirect();
        $this->assertModelMissing($testimonial);
    }

    public function test_testimonial_rating_must_be_valid(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('testimonials.store'), [
                'client_name' => 'Test Client',
                'quote' => 'Test quote',
                'rating' => 10, // Invalid: should be 1-5
            ]);

        $response->assertSessionHasErrors('rating');
    }

    public function test_non_admin_cannot_manage_testimonials(): void
    {
        $user = User::factory()->create();
        $user->assignRole('crew');

        $response = $this->actingAs($user)
            ->post(route('testimonials.store'), [
                'client_name' => 'Unauthorized',
                'quote' => 'Test',
                'rating' => 5,
            ]);

        $response->assertStatus(403);
    }

    public function test_testimonials_display_on_homepage(): void
    {
        Testimonial::factory()->count(3)->create();

        $response = $this->get('/');

        $response->assertStatus(200);
        // Testimonials should be visible
        $response->assertSee('What clients', false);
    }
}
