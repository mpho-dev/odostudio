<?php

namespace Tests\Unit;

use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SiteSettingTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_store_and_retrieve_string_value(): void
    {
        SiteSetting::set('site_name', 'MediaWeb Studio');

        $this->assertEquals('MediaWeb Studio', SiteSetting::get('site_name'));
    }

    #[Test]
    public function it_can_store_and_retrieve_integer_value(): void
    {
        SiteSetting::set('items_per_page', 20);

        $this->assertEquals(20, SiteSetting::get('items_per_page'));
    }

    #[Test]
    public function it_can_store_and_retrieve_boolean_value(): void
    {
        SiteSetting::set('maintenance_mode', true);

        $value = SiteSetting::get('maintenance_mode');
        $this->assertEquals('1', $value);
    }

    #[Test]
    public function it_returns_default_when_key_not_found(): void
    {
        $value = SiteSetting::get('non_existent_key', 'default_value');

        $this->assertEquals('default_value', $value);
    }

    #[Test]
    public function it_can_update_existing_setting(): void
    {
        SiteSetting::set('site_name', 'Old Name');
        SiteSetting::set('site_name', 'New Name');

        $this->assertEquals('New Name', SiteSetting::get('site_name'));
        $this->assertDatabaseCount('site_settings', 1);
    }

    #[Test]
    public function it_can_store_null_value(): void
    {
        SiteSetting::set('optional_field', null);

        $this->assertNull(SiteSetting::get('optional_field'));
    }

    #[Test]
    public function it_has_fillable_key_and_value(): void
    {
        $setting = SiteSetting::create([
            'key' => 'custom_key',
            'value' => 'custom_value',
            'type' => 'string',
        ]);

        $this->assertEquals('custom_key', $setting->key);
        $this->assertEquals('custom_value', $setting->value);
    }
}
