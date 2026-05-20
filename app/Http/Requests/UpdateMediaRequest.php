<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('media'));
    }

    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'tags' => 'nullable|array',
            'tags.*' => 'required|string|max:255',
            'collections' => 'nullable|array',
            'collections.*' => 'required|exists:collections,id',
            'project_id' => 'nullable|exists:projects,id',
        ];
    }
}
