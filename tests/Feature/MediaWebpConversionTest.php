<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaWebpConversionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);
    }

    public function test_uploaded_webp_images_are_stored_as_is(): void
    {
        $response = $this->post('/admin/media', [
            'file' => UploadedFile::fake()->image('photo.webp', 800, 600),
            'title' => 'Test Photo',
            'media_type' => 'image',
        ]);

        $response->assertRedirect();

        $media = Media::first();
        $this->assertNotNull($media);
        $this->assertStringEndsWith('.webp', $media->file_path);
        Storage::disk('public')->assertExists($media->file_path);
    }

    public function test_uploaded_jpg_images_are_stored_without_conversion(): void
    {
        $response = $this->post('/admin/media', [
            'file' => UploadedFile::fake()->image('photo.jpg', 800, 600),
            'title' => 'Test Photo',
            'media_type' => 'image',
        ]);

        $response->assertRedirect();

        $media = Media::first();
        $this->assertNotNull($media);
        $this->assertStringEndsWith('.jpg', $media->file_path);
        Storage::disk('public')->assertExists($media->file_path);
    }

    public function test_site_settings_images_are_converted_to_webp(): void
    {
        $response = $this->post('/admin/site-config', [
            'hero_bg_image' => UploadedFile::fake()->image('hero.png', 1920, 1080),
            'about_portrait_image' => UploadedFile::fake()->image('portrait.jpg', 800, 600),
            'hero_name' => 'Test Studio',
        ]);

        $response->assertRedirect();

        $heroPath = str_replace('/storage/', '', SiteSetting::get('hero_bg_image'));
        $portraitPath = str_replace('/storage/', '', SiteSetting::get('about_portrait_image'));

        $this->assertStringEndsWith('.webp', $heroPath);
        $this->assertStringEndsWith('.webp', $portraitPath);

        Storage::disk('public')->assertExists($heroPath);
        Storage::disk('public')->assertExists($portraitPath);
    }

    public function test_non_image_media_is_not_converted_to_webp(): void
    {
        $fakeFile = UploadedFile::fake()->create('video.mp4', 2048, 'video/mp4');

        $this->assertEquals('video/mp4', $fakeFile->getMimeType());
        $this->assertGreaterThan(1024, $fakeFile->getSize());

        $response = $this->post('/admin/media', [
            'file' => $fakeFile,
            'title' => 'Test Video',
            'media_type' => 'video',
        ]);

        $this->assertContains($response->status(), [302, 422], 'Expected redirect or validation error');
    }
}
