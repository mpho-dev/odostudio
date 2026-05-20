<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|max:102400|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,webm',
            'project_id' => 'nullable|exists:projects,id',
            'type' => 'nullable|string|in:image,video',
            'is_hero' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'caption' => 'nullable|string|max:500',
        ];
    }
}
