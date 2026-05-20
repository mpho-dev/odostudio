<?php

namespace Tests\Unit;

use App\Models\EmailConfiguration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EmailConfigurationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_get_config_by_key(): void
    {
        EmailConfiguration::create([
            'key' => 'booking_request_email_from',
            'value' => 'test@example.com',
        ]);

        $value = EmailConfiguration::getConfig('booking_request_email_from');

        $this->assertEquals('test@example.com', $value);
    }

    #[Test]
    public function it_returns_default_when_key_not_found(): void
    {
        $value = EmailConfiguration::getConfig('non_existent_key', 'default_value');

        $this->assertEquals('default_value', $value);
    }

    #[Test]
    public function it_returns_null_when_key_not_found_and_no_default(): void
    {
        $value = EmailConfiguration::getConfig('non_existent_key');

        $this->assertNull($value);
    }

    #[Test]
    public function it_can_set_config_creating_new_record(): void
    {
        $result = EmailConfiguration::setConfig('new_key', 'new_value');

        $this->assertDatabaseHas('email_configurations', [
            'key' => 'new_key',
        ]);
        $this->assertEquals('new_value', EmailConfiguration::getConfig('new_key'));
    }

    #[Test]
    public function it_can_set_config_updating_existing_record(): void
    {
        EmailConfiguration::create([
            'key' => 'existing_key',
            'value' => 'old_value',
        ]);

        EmailConfiguration::setConfig('existing_key', 'updated_value');

        $this->assertEquals('updated_value', EmailConfiguration::getConfig('existing_key'));
        $this->assertDatabaseCount('email_configurations', 1);
    }

    #[Test]
    public function value_is_encrypted_in_database(): void
    {
        $plainValue = 'sensitive_email_data';
        EmailConfiguration::setConfig('encrypted_key', $plainValue);

        $rawRecord = \DB::table('email_configurations')
            ->where('key', 'encrypted_key')
            ->first();

        $this->assertNotEquals($plainValue, $rawRecord->value);
        $this->assertEquals($plainValue, EmailConfiguration::getConfig('encrypted_key'));
    }

    #[Test]
    public function it_can_store_multiple_different_configs(): void
    {
        EmailConfiguration::setConfig('key1', 'value1');
        EmailConfiguration::setConfig('key2', 'value2');
        EmailConfiguration::setConfig('key3', 'value3');

        $this->assertEquals('value1', EmailConfiguration::getConfig('key1'));
        $this->assertEquals('value2', EmailConfiguration::getConfig('key2'));
        $this->assertEquals('value3', EmailConfiguration::getConfig('key3'));
        $this->assertDatabaseCount('email_configurations', 3);
    }

    #[Test]
    public function it_can_update_specific_config_without_affecting_others(): void
    {
        EmailConfiguration::setConfig('config_a', 'value_a');
        EmailConfiguration::setConfig('config_b', 'value_b');

        EmailConfiguration::setConfig('config_a', 'updated_a');

        $this->assertEquals('updated_a', EmailConfiguration::getConfig('config_a'));
        $this->assertEquals('value_b', EmailConfiguration::getConfig('config_b'));
    }

    #[Test]
    public function it_handles_empty_string_values(): void
    {
        EmailConfiguration::setConfig('empty_key', '');

        $this->assertEquals('', EmailConfiguration::getConfig('empty_key'));
    }

    #[Test]
    public function it_handles_long_values(): void
    {
        $longValue = str_repeat('a', 10000);
        EmailConfiguration::setConfig('long_key', $longValue);

        $this->assertEquals($longValue, EmailConfiguration::getConfig('long_key'));
    }

    #[Test]
    public function it_uses_correct_fillable_attributes(): void
    {
        $config = EmailConfiguration::create([
            'key' => 'test_key',
            'value' => 'test_value',
        ]);

        $this->assertEquals('test_key', $config->key);
        $this->assertEquals('test_value', $config->value);
    }
}
