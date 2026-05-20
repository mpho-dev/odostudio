<?php

namespace Tests\Feature;

use App\Models\ProcessStep;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProcessStepManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    public function test_admin_can_create_process_step(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('process-steps.store'), [
                'step_number' => 1,
                'title' => 'Initial Consultation',
                'description' => 'We discuss your vision and requirements',
                'display_order' => 1,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('process_steps', [
            'title' => 'Initial Consultation',
            'display_order' => 1,
        ]);
    }

    public function test_admin_can_update_process_step(): void
    {
        $step = ProcessStep::factory()->create([
            'title' => 'Original Title',
            'display_order' => 1,
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('process-steps.update', $step), [
                'step_number' => $step->step_number,
                'title' => 'Updated Title',
                'description' => 'Updated description',
                'display_order' => 2,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('process_steps', [
            'id' => $step->id,
            'title' => 'Updated Title',
            'display_order' => 2,
        ]);
    }

    public function test_admin_can_delete_process_step(): void
    {
        $step = ProcessStep::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('process-steps.destroy', $step));

        $response->assertRedirect();
        $this->assertSoftDeleted($step);
    }

    public function test_process_step_requires_valid_data(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('process-steps.store'), [
                'step_number' => 1,
                'title' => '', // Empty title
            ]);

        $response->assertSessionHasErrors();
    }

    public function test_process_steps_display_in_order(): void
    {
        ProcessStep::factory()->create(['title' => 'First', 'display_order' => 1]);
        ProcessStep::factory()->create(['title' => 'Second', 'display_order' => 2]);
        ProcessStep::factory()->create(['title' => 'Third', 'display_order' => 3]);

        $response = $this->get('/');

        $response->assertStatus(200);
        // Verify steps appear in the correct order
        $content = $response->getContent();
        $firstPos = strpos($content, 'First');
        $secondPos = strpos($content, 'Second');
        $thirdPos = strpos($content, 'Third');

        $this->assertLessThan($secondPos, $firstPos);
        $this->assertLessThan($thirdPos, $secondPos);
    }

    public function test_non_admin_cannot_manage_process_steps(): void
    {
        $user = User::factory()->create();
        $user->assignRole('crew');

        $response = $this->actingAs($user)
            ->post(route('process-steps.store'), [
                'step_number' => 1,
                'title' => 'Unauthorized Step',
                'description' => 'Test',
                'display_order' => 1,
            ]);

        $response->assertStatus(403);
    }

    public function test_process_steps_display_on_homepage(): void
    {
        ProcessStep::factory()->count(4)->create();

        $response = $this->get('/');

        $response->assertStatus(200);
        // Process section should be visible
        $response->assertSee('Process', false);
    }
}
