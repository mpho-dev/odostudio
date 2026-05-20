<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_returns_valid_xml(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
    }

    public function test_sitemap_contains_static_pages(): void
    {
        $response = $this->get('/sitemap.xml');

        $content = $response->getContent();
        $this->assertStringContainsString('<loc>'.route('home').'</loc>', $content);
        $this->assertStringContainsString('<loc>'.route('portfolio').'</loc>', $content);
        $this->assertStringContainsString('<loc>'.route('contact.form').'</loc>', $content);
    }

    public function test_sitemap_includes_published_projects(): void
    {
        // Create featured projects
        $project1 = Project::factory()->create(['is_featured' => true, 'order' => 1]);
        $project2 = Project::factory()->create(['is_featured' => true, 'order' => 2]);

        // Create non-featured project (should not appear)
        Project::factory()->create(['is_featured' => false]);

        $response = $this->get('/sitemap.xml');

        $content = $response->getContent();
        $this->assertStringContainsString(route('portfolio.project', $project1->slug), $content);
        $this->assertStringContainsString(route('portfolio.project', $project2->slug), $content);
    }

    public function test_sitemap_has_required_xml_structure(): void
    {
        $response = $this->get('/sitemap.xml');

        $content = $response->getContent();
        $this->assertStringStartsWith('<?xml', $content);
        $this->assertStringContainsString('</urlset>', $content);
        $this->assertStringContainsString('<url>', $content);
        $this->assertStringContainsString('</url>', $content);
        $this->assertStringContainsString('<loc>', $content);
        $this->assertStringContainsString('<lastmod>', $content);
        $this->assertStringContainsString('<changefreq>', $content);
        $this->assertStringContainsString('<priority>', $content);
    }

    public function test_sitemap_priorities_are_correct(): void
    {
        $response = $this->get('/sitemap.xml');
        $content = $response->getContent();

        // Create XML from response
        $xml = simplexml_load_string($content);
        $urls = $xml->url;

        // Check that we have at least 3 static URLs
        $this->assertGreaterThanOrEqual(3, count($urls));

        // Verify priorities exist and are numeric
        foreach ($urls as $url) {
            $priority = (string) $url->priority;
            $this->assertMatchesRegularExpression('/^\d\.\d$/', $priority);
            $this->assertGreaterThanOrEqual(0.0, (float) $priority);
            $this->assertLessThanOrEqual(1.0, (float) $priority);
        }
    }
}
