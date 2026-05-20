<?php

namespace Tests\Unit;

use App\Services\HtmlPurifier;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

class HtmlPurifierTest extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';

        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    public function test_it_cleans_dangerous_html()
    {
        $input = '<script>alert("xss")</script><p>Hello</p>';
        $cleaned = HtmlPurifier::clean($input);

        $this->assertStringNotContainsString('<script>', $cleaned);
        $this->assertStringContainsString('<p>Hello</p>', $cleaned);
    }

    public function test_it_allows_formatting_tags()
    {
        $input = '<strong>Bold</strong> <em>Italic</em> <u>Underline</u>';
        $cleaned = HtmlPurifier::clean($input);

        $this->assertStringContainsString('<strong>Bold</strong>', $cleaned);
        $this->assertStringContainsString('<em>Italic</em>', $cleaned);
        $this->assertStringContainsString('<u>Underline</u>', $cleaned);
    }

    public function test_it_allows_lists()
    {
        $input = '<ul><li>Item 1</li></ul>';
        $cleaned = HtmlPurifier::clean($input);

        $this->assertStringContainsString('<ul>', $cleaned);
        $this->assertStringContainsString('<li>Item 1</li>', $cleaned);
    }

    public function test_validate_returns_false_for_scripts()
    {
        $input = '<script>alert("xss")</script>';
        $this->assertFalse(HtmlPurifier::validate($input));
    }

    public function test_validate_returns_false_for_event_handlers()
    {
        $input = '<div onclick="alert(1)">Click me</div>';
        $this->assertFalse(HtmlPurifier::validate($input));
    }

    public function test_validate_returns_true_for_safe_html()
    {
        $input = '<p>Safe content</p>';
        $this->assertTrue(HtmlPurifier::validate($input));
    }
}
