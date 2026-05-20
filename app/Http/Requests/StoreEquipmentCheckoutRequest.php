<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEquipmentCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['admin', 'manager']);
    }

    public function rules(): array
    {
        return [
            'equipment_items' => 'required|array|min:1',
            'equipment_items.*' => 'exists:equipment_items,id',
            'booking_id' => 'required|exists:bookings,id',
            'user_id' => 'required|exists:users,id',
            'checkout_date' => 'required|date',
            'due_date' => 'required|date|after:checkout_date',
            'notes' => 'nullable|string',
        ];
    }
}
