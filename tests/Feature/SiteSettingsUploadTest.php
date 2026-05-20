<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SiteSettingsUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_upload_hero_background_image(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)
            ->post(route('admin.site-config.update'), [
                'hero_bg_image' => UploadedFile::fake()->image('banner.jpg'),
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $setting = SiteSetting::where('key', 'hero_bg_image')->first();
        $this->assertNotNull($setting);
        $this->assertStringEndsWith('.webp', $setting->value);

        // ensure file was stored on public disk (strip the leading "/storage/")
        Storage::disk('public')->assertExists(ltrim(str_replace('/storage/', '', $setting->value), '/'));
    }

    public function test_non_admin_cannot_update_site_settings(): void
    {
        $user = User::factory()->create();
        $user->assignRole('crew');

        $response = $this->actingAs($user)
            ->post(route('admin.site-config.update'), [
                'hero_eyebrow' => 'Should not stick',
            ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('site_settings', ['key' => 'hero_eyebrow']);
    }

    public function test_rich_text_is_sanitized_and_displayed(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // include paragraph tags and a malicious script
        $payload = [
            'hero_title' => '<p>Hello <strong>world</strong></p>',
            'about_body_1' => 'Intro paragraph <script>alert(1)</script>',
            'about_body_2' => 'Second paragraph',
            'cta_subtext' => '<p>Click <a href="#">here</a></p>',
        ];

        $response = $this->actingAs($admin)
            ->post(route('admin.site-config.update'), $payload);

        $response->assertSessionHas('success');

        // verify sanitized storage (script removed)
        $this->assertDatabaseHas('site_settings', ['key' => 'hero_title']);
        $saved = SiteSetting::where('key', 'about_body_1')->first();
        $this->assertNotNull($saved);
        $this->assertStringNotContainsString('<script>', $saved->value);

        // visit front page and ensure HTML is rendered (not escaped)
        $home = $this->get('/');
        $home->assertStatus(200);
        $home->assertSee('<p>Hello <strong>world</strong></p>', false);
        $home->assertDontSee('&lt;script&gt;', false);

        // about body fields are wrapped in <p> by the Blade view
        $home->assertSee('<p>Intro paragraph </p>', false);
        $home->assertSee('<p>Second paragraph</p>', false);

        // malicious payload text should not survive sanitization
        $home->assertDontSee('alert(1)');
    }
}
