<?php

namespace App\Services;

use App\Models\EmailTemplate;
use App\Traits\HasEmailPlaceholders;

class EmailTemplateService
{
    use HasEmailPlaceholders;

    /**
     * Get a rendered template by its slug and data.
     */
    public function render(string $slug, $data = []): array
    {
        $template = EmailTemplate::where('slug', $slug)->first();

        if (!$template || !$template->is_active) {
            return [
                'subject' => '',
                'body' => '',
            ];
        }

        return [
            'subject' => $this->processPlaceholders($template->subject, $data),
            'body' => $this->processPlaceholders($template->body, $data),
        ];
    }
}
