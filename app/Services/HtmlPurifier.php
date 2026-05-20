<?php

namespace App\Services;

use Mews\Purifier\Facades\Purifier;

class HtmlPurifier
{
    /**
     * Purify HTML from Quill editor output.
     *
     * Allows: bold, italic, underline, links, lists, blockquotes, headings
     * Blocks: scripts, iframes, external styles, event handlers
     */
    public static function clean(string $html): string
    {
        // Use Laravel's built-in purifier
        return Purifier::clean($html);
    }

    /**
     * Validate and sanitize for storage.
     */
    public static function validate(string $html, int $maxLength = 10000): bool
    {
        // Check length
        if (strlen($html) > $maxLength) {
            return false;
        }

        // Check for dangerous patterns
        if (preg_match('/<script|javascript:|onerror|onclick/i', $html)) {
            return false;
        }

        return true;
    }
}
