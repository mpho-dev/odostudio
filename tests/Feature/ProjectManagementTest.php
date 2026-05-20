<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_project_with_media(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $media = Media::factory()->create();

        $response = $this->actingAs($admin)->post(route('projects.store'), [
            'title' => 'My Project',
            'description' => 'Project Description',
            'selected_media' => [$media->id],
        ]);

        $response->assertRedirect(route('projects.index'));
        $this->assertDatabaseHas('projects', ['title' => 'My Project']);
        $this->assertEquals(Project::first()->id, $media->refresh()->project_id);
    }

    public function test_admin_can_update_project(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $project = Project::factory()->create();
        $media = Media::factory()->create();

        $response = $this->actingAs($admin)->put(route('projects.update', $project), [
            'title' => 'Updated Project',
            'slug' => 'updated-project',
            'description' => 'Updated Description',
            'selected_media' => [$media->id],
        ]);

        $response->assertRedirect(route('projects.index'));
        $this->assertDatabaseHas('projects', ['title' => 'Updated Project']);
        $this->assertEquals($project->id, $media->refresh()->project_id);
    }
}
