<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['admin', 'manager']);
    }

    public function rules(): array
    {
        return [
            'investment_tier_id' => 'required|exists:investment_tiers,id',
            'event_date' => 'nullable|date',
            'event_time' => 'nullable',
            'event_location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'photographers' => 'nullable|array',
            'photographers.*' => 'exists:users,id',
            'videographers' => 'nullable|array',
            'videographers.*' => 'exists:users,id',
        ];
    }
}
