<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCollectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('collection'));
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|size:7|regex:/^#[0-9a-fA-F]{6}$/',
            'media_ids' => 'nullable|array',
            'media_ids.*' => 'integer|exists:media,id',
        ];
    }

    public function messages(): array
    {
        return [
            'color.regex' => 'Color must be a valid hex color code (e.g., #c9a84c).',
            'color.size' => 'Color must be exactly 7 characters (e.g., #c9a84c).',
        ];
    }
}
