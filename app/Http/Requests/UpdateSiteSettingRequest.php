<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'hero_bg_image' => 'nullable|image|max:10240',
            'about_portrait_image' => 'nullable|image|max:10240',
            'remove_hero_bg_image' => 'nullable|boolean',
            'remove_about_portrait_image' => 'nullable|boolean',
            'hero_eyebrow' => 'nullable|string|max:255',
            'hero_name' => 'nullable|string|max:255',
            'hero_title' => 'nullable|string|max:500',
            'about_narrative_eyebrow' => 'nullable|string|max:255',
            'about_eyebrow' => 'nullable|string|max:255',
            'about_title' => 'nullable|string|max:500',
            'about_body_1' => 'nullable|string',
            'about_body_2' => 'nullable|string',
            'cta_title' => 'nullable|string|max:255',
            'cta_subtext' => 'nullable|string|max:255',
            'stats_years' => 'nullable|string|max:10',
            'stats_projects' => 'nullable|string|max:10',
            'stats_awards' => 'nullable|string|max:10',
        ];
    }
}
