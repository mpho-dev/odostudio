<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_view_portfolio(): void
    {
        \App\Models\Project::factory()->create();

        $response = $this->get('/portfolio');

        $response->assertStatus(200);
        $response->assertViewHas('projects');
        $response->assertViewHas('tags');
        $response->assertViewHas('categories');
        $this->assertEquals(1, $response->viewData('projects')->count());
    }

    public function test_portfolio_only_shows_projects(): void
    {
        $project = \App\Models\Project::factory()->create(['title' => 'Featured Project']);
        Media::factory(3)->image()->create();

        $response = $this->get('/portfolio');

        $response->assertStatus(200);
        $response->assertSee('Featured Project');
        $this->assertEquals(1, $response->viewData('projects')->count());
    }

    public function test_portfolio_can_be_filtered_by_category(): void
    {
        \App\Models\Project::factory()->create(['title' => 'Wedding Project', 'category' => 'Wedding']);
        \App\Models\Project::factory()->create(['title' => 'Brand Project', 'category' => 'Brand']);

        $response = $this->get(route('portfolio', ['category' => 'Wedding']));

        $response->assertStatus(200);
        $response->assertSee('Wedding Project');
        $response->assertDontSee('Brand Project');
        $this->assertEquals(1, $response->viewData('projects')->count());
    }

    public function test_guest_without_login_can_view_portfolio(): void
    {
        \App\Models\Project::factory()->create();

        $response = $this->get('/portfolio');

        $response->assertStatus(200);
        $response->assertViewHas('projects');
    }

    public function test_admin_can_upload_media(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post('/admin/media', [
            'title' => 'Beautiful Performance',
            'file' => UploadedFile::fake()->image('photo.jpg', 800, 600),
            'media_type' => 'image',
        ]);

        $this->assertDatabaseHas('media', [
            'title' => 'Beautiful Performance',
            'media_type' => 'image',
        ]);
    }

    public function test_admin_can_delete_media(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $media = Media::factory()->image()->create([
            'uploaded_by' => $admin->id,
        ]);

        $response = $this->actingAs($admin)
            ->delete('/admin/media/'.$media->id);

        $response->assertRedirect();
    }

    public function test_media_factory_creates_different_types(): void
    {
        $image = Media::factory()->image()->create();
        $video = Media::factory()->video()->create();
        $gif = Media::factory()->gif()->create();

        $this->assertEquals('image', $image->media_type);
        $this->assertEquals('video', $video->media_type);
        $this->assertEquals('gif', $gif->media_type);
    }

    public function test_non_privileged_user_cannot_upload_media(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/admin/media', [
            'title' => 'Test',
            'file' => UploadedFile::fake()->image('photo.jpg', 800, 600),
            'media_type' => 'image',
        ]);

        $response->assertForbidden();
    }

    public function test_photographer_can_upload_media(): void
    {
        Storage::fake('public');

        $photographer = User::factory()->create();
        $photographer->assignRole('crew');
        $this->assertTrue($photographer->hasRole('crew'), 'photographer should have the correct role');

        $this->assertTrue(Gate::forUser($photographer)->allows('create', Media::class));

        $response = $this->actingAs($photographer)->post('/admin/media', [
            'title' => 'Artist Submission',
            'file' => UploadedFile::fake()->image('camera.jpg', 800, 600),
            'media_type' => 'image',
        ]);

        // should succeed and flash a message
        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('media', [
            'title' => 'Artist Submission',
            'uploaded_by' => $photographer->id,
            'media_type' => 'image',
        ]);
    }
}
