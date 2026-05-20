<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignCrewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['admin', 'manager']);
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = \App\Models\User::find($value);
                    $role = $this->input('role');

                    if ($user && $user->crew_specialty !== 'both' && $user->crew_specialty !== $role) {
                        $fail("Crew member's specialty does not match assigned role. {$user->name} is a {$user->crew_specialty} but being assigned as {$role}.");
                    }
                },
            ],
            'role' => ['required', Rule::in(['photographer', 'videographer'])],
        ];
    }
}
