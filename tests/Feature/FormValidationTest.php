<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormValidationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    // ========================================
    // CONTACT FORM VALIDATION
    // ========================================

    public function test_contact_form_requires_name(): void
    {
        $response = $this->post(route('contact.store'), [
            'surname' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_contact_form_requires_surname(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'John',
            'email' => 'john@example.com',
            'phone' => '1234567890',
        ]);

        $response->assertSessionHasErrors('surname');
    }

    public function test_contact_form_requires_email(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'John',
            'surname' => 'Doe',
            'phone' => '1234567890',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_contact_form_requires_valid_email_format(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'not-an-email',
            'phone' => '1234567890',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_contact_form_requires_phone(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john@example.com',
        ]);

        $response->assertSessionHasErrors('phone');
    }

    public function test_contact_form_validates_email_max_length(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => str_repeat('a', 250).'@example.com',
            'phone' => '1234567890',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_contact_form_validates_name_max_length(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => str_repeat('a', 256),
            'surname' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_contact_form_validates_phone_max_length(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john@example.com',
            'phone' => str_repeat('1', 21),
        ]);

        $response->assertSessionHasErrors('phone');
    }

    // ========================================
    // SERVICE VALIDATION
    // ========================================

    public function test_service_requires_title(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('services.store'), [
                'description' => 'Test description',
                'starting_price' => 5000,
            ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_service_validates_title_max_length(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('services.store'), [
                'title' => str_repeat('a', 256),
                'description' => 'Test description',
                'starting_price' => 5000,
            ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_service_validates_price_is_numeric(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('services.store'), [
                'title' => 'Test Service',
                'description' => 'Test description',
                'starting_price' => 'not-a-number',
            ]);

        $response->assertSessionHasErrors('starting_price');
    }

    public function test_service_validates_order_is_integer(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('services.store'), [
                'title' => 'Test Service',
                'description' => 'Test description',
                'starting_price' => 5000,
                'order' => 'not-a-number',
            ]);

        $response->assertSessionHasErrors('order');
    }

    // ========================================
    // INVESTMENT TIER VALIDATION
    // ========================================

    public function test_investment_tier_requires_name(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('investment-tiers.store'), [
                'price' => 10000,
                'tier_label' => 'Premium',
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_investment_tier_requires_price(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('investment-tiers.store'), [
                'name' => 'Premium Package',
                'tier_label' => 'Premium',
            ]);

        $response->assertSessionHasErrors('price');
    }

    public function test_investment_tier_validates_price_is_numeric(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('investment-tiers.store'), [
                'name' => 'Premium Package',
                'price' => 'not-a-number',
                'tier_label' => 'Premium',
            ]);

        $response->assertSessionHasErrors('price');
    }

    public function test_investment_tier_validates_order_is_integer(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('investment-tiers.store'), [
                'name' => 'Premium Package',
                'price' => 10000,
                'tier_label' => 'Premium',
                'order' => 'not-a-number',
            ]);

        $response->assertSessionHasErrors('order');
    }

    // ========================================
    // TESTIMONIAL VALIDATION
    // ========================================

    public function test_testimonial_requires_client_name(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('testimonials.store'), [
                'quote' => 'Great service!',
                'rating' => 5,
            ]);

        $response->assertSessionHasErrors('client_name');
    }

    public function test_testimonial_requires_quote(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('testimonials.store'), [
                'client_name' => 'John Doe',
                'rating' => 5,
            ]);

        $response->assertSessionHasErrors('quote');
    }

    public function test_testimonial_requires_rating(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('testimonials.store'), [
                'client_name' => 'John Doe',
                'quote' => 'Great service!',
            ]);

        $response->assertSessionHasErrors('rating');
    }

    public function test_testimonial_validates_rating_range(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('testimonials.store'), [
                'client_name' => 'John Doe',
                'quote' => 'Great service!',
                'rating' => 6,
            ]);

        $response->assertSessionHasErrors('rating');
    }

    public function test_testimonial_validates_rating_minimum(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('testimonials.store'), [
                'client_name' => 'John Doe',
                'quote' => 'Great service!',
                'rating' => 0,
            ]);

        $response->assertSessionHasErrors('rating');
    }

    // ========================================
    // PROJECT VALIDATION
    // ========================================

    public function test_project_requires_title(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('projects.store'), [
                'description' => 'Test project',
            ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_project_validates_slug_unique(): void
    {
        Project::factory()->create(['slug' => 'test-project']);

        $response = $this->actingAs($this->admin)
            ->post(route('projects.store'), [
                'title' => 'Test Project',
                'slug' => 'test-project',
                'description' => 'Test project',
            ]);

        $response->assertSessionHasErrors('slug');
    }

    public function test_project_validates_title_max_length(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('projects.store'), [
                'title' => str_repeat('a', 256),
                'description' => 'Test project',
            ]);

        $response->assertSessionHasErrors('title');
    }

    // ========================================
    // USER MANAGEMENT VALIDATION
    // ========================================

    public function test_user_creation_requires_name(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.store'), [
                'email' => 'newuser@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_user_creation_requires_email(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.store'), [
                'name' => 'New User',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_user_creation_requires_valid_email(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.store'), [
                'name' => 'New User',
                'email' => 'not-an-email',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_user_creation_requires_password(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.store'), [
                'name' => 'New User',
                'email' => 'newuser@example.com',
            ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_user_creation_requires_password_confirmation(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.store'), [
                'name' => 'New User',
                'email' => 'newuser@example.com',
                'password' => 'password123',
                'password_confirmation' => 'different-password',
            ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_user_creation_validates_email_unique(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.users.store'), [
                'name' => 'New User',
                'email' => 'existing@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertSessionHasErrors('email');
    }

    // ========================================
    // PROCESS STEP VALIDATION
    // ========================================

    public function test_process_step_requires_title(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('process-steps.store'), [
                'description' => 'Test step',
            ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_process_step_validates_display_order_is_integer(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('process-steps.store'), [
                'title' => 'Test Step',
                'description' => 'Test description',
                'display_order' => 'not-a-number',
            ]);

        $response->assertSessionHasErrors('display_order');
    }
}
