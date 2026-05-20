<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavHomeButtonTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_button_shown_on_portfolio_and_contact_pages_when_enabled()
    {
        // By default, the setting is seeded as enabled
        $response = $this->get(route('portfolio'));
        $response->assertOk();
        $response->assertSee('<a href="'.route('home').'" class="nav-link" aria-label="Home" title="Home">', false);

        $response = $this->get(route('contact.form'));
        $response->assertOk();
        $response->assertSee('<a href="'.route('home').'" class="nav-link" aria-label="Home" title="Home">', false);
    }

    public function test_home_button_hidden_when_disabled()
    {
        SiteSetting::set('home_button_enabled', false);

        $response = $this->get(route('portfolio'));
        $response->assertOk();
        $response->assertDontSee('<a href="'.route('home').'" class="nav-link" aria-label="Home" title="Home">', false);

        $response = $this->get(route('contact.form'));
        $response->assertOk();
        $response->assertDontSee('<a href="'.route('home').'" class="nav-link" aria-label="Home" title="Home">', false);
    }
}
