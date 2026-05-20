<?php

namespace Tests\Feature;

use Database\Seeders\InvestmentTierSeeder;
use Database\Seeders\SiteSettingSeeder;
use Database\Seeders\TestimonialSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_seeded_investment_tiers_and_testimonials(): void
    {
        $this->seed([
            SiteSettingSeeder::class,
            InvestmentTierSeeder::class,
            TestimonialSeeder::class,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);

        // Hero & about copy from SiteSettingSeeder / HTML
        $response->assertSee('Photography &amp; Videography', false);
        $response->assertSee('Odo <em>Studio</em>', false);
        $response->assertSee('Stories told through<br><em>light & motion</em>', false);

        // Investment tiers (packages) seeded from HTML
        $response->assertSee('The Classic', false);
        $response->assertSee('Photo + Film', false);
        $response->assertSee('Full Production', false);
        $response->assertSee('8 hours photography coverage', false);
        $response->assertSee('2 photographer + 1 videographer', false);
        $response->assertSee('Feature-length film (20+ min)', false);

        // CTA copy
        $response->assertSee('Let\'s create <em>something</em>', false);
        $response->assertSee('Every great image starts with a conversation.', false);
    }
}
