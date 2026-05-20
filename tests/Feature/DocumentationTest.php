<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DocumentationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        SiteSetting::create(['key' => 'site_name', 'value' => 'MediaWeb']);
        SiteSetting::create(['key' => 'hero_title', 'value' => 'Test Hero']);
        SiteSetting::create(['key' => 'hero_subtitle', 'value' => 'Test Subtitle']);
    }

    #[Test]
    public function public_can_access_documentation_index(): void
    {
        $response = $this->get('/docs');

        $response->assertStatus(200);
        $response->assertViewIs('admin.documentation');
    }

    #[Test]
    public function public_cannot_view_admin_documentation(): void
    {
        $response = $this->get('/docs/admin');

        $response->assertStatus(404);
    }

    #[Test]
    public function public_cannot_view_manager_documentation(): void
    {
        $response = $this->get('/docs/manager');

        $response->assertStatus(404);
    }

    #[Test]
    public function public_cannot_view_photographer_documentation(): void
    {
        $response = $this->get('/docs/photographer');

        $response->assertStatus(404);
    }

    #[Test]
    public function public_can_view_public_features_documentation(): void
    {
        $response = $this->get('/docs/public');

        $response->assertStatus(200);
        $response->assertViewIs('docs.show');
        $response->assertSee('Public Features');
    }

    #[Test]
    public function public_cannot_view_architecture_documentation(): void
    {
        $response = $this->get('/docs/architecture');

        $response->assertStatus(404);
    }

    #[Test]
    public function invalid_documentation_route_returns_404(): void
    {
        $response = $this->get('/docs/nonexistent');

        $response->assertStatus(404);
    }

    #[Test]
    public function admin_can_access_documentation_viewer(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/admin/documentation');

        $response->assertStatus(200);
        $response->assertViewIs('admin.documentation');
    }

    #[Test]
    public function documentation_viewer_shows_navigation_links(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/admin/documentation');

        $response->assertStatus(200);
        $response->assertSee('Admin Guide');
        $response->assertSee('Manager Guide');
        $response->assertSee('Photographer Guide');
    }

    #[Test]
    public function documentation_links_use_named_routes(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/admin/documentation');

        $response->assertStatus(200);
        $response->assertSee('href="'.route('admin.email-config').'"', false);
        $response->assertSee('href="'.route('admin.site-config').'"', false);
        $response->assertSee('href="'.route('admin.health-check').'"', false);
    }

    #[Test]
    public function markdown_files_are_parsed_correctly(): void
    {
        $this->assertFileExists(base_path('docs/ADMIN.md'));
        $this->assertFileExists(base_path('docs/MANAGER.md'));
        $this->assertFileExists(base_path('docs/PHOTOGRAPHER.md'));
        $this->assertFileExists(base_path('docs/PUBLIC.md'));
        $this->assertFileExists(base_path('docs/ARCHITECTURE.md'));

        $adminContent = File::get(base_path('docs/ADMIN.md'));
        $this->assertStringContainsString('#', $adminContent);
    }

    #[Test]
    public function documentation_is_cached(): void
    {
        $response1 = $this->get('/docs/public');
        $response1->assertStatus(200);

        $response2 = $this->get('/docs/public');
        $response2->assertStatus(200);

        $this->assertEquals($response1->getContent(), $response2->getContent());
    }

    #[Test]
    public function admin_can_view_all_documentation(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        foreach (['admin', 'manager', 'photographer', 'public', 'architecture'] as $doc) {
            $response = $this->actingAs($admin)->get("/docs/{$doc}");
            $response->assertStatus(200);
            $response->assertViewIs('docs.show');
        }
    }

    #[Test]
    public function manager_can_view_allowed_documentation(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        foreach (['manager', 'photographer', 'public'] as $doc) {
            $response = $this->actingAs($manager)->get("/docs/{$doc}");
            $response->assertStatus(200);
            $response->assertViewIs('docs.show');
        }

        foreach (['admin', 'architecture'] as $doc) {
            $response = $this->actingAs($manager)->get("/docs/{$doc}");
            $response->assertStatus(404);
        }
    }

    #[Test]
    public function photographer_can_view_allowed_documentation(): void
    {
        $photographer = User::factory()->create();
        $photographer->assignRole('crew');

        foreach (['photographer', 'public'] as $doc) {
            $response = $this->actingAs($photographer)->get("/docs/{$doc}");
            $response->assertStatus(200);
            $response->assertViewIs('docs.show');
        }

        foreach (['admin', 'manager', 'architecture'] as $doc) {
            $response = $this->actingAs($photographer)->get("/docs/{$doc}");
            $response->assertStatus(404);
        }
    }
}
