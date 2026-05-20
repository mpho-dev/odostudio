<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmailConfigRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'booking_request_email_from' => 'required|email',
            'booking_request_email_to' => 'required|email',
            'booking_request_email_subject' => ['required', 'string', 'max:255', 'regex:/^[^<>]*$/'],
            'booking_request_email_body' => 'required|string',
            'invoice_sent_email_from' => 'required|email',
            'invoice_sent_email_subject' => ['required', 'string', 'max:255', 'regex:/^[^<>]*$/'],
            'invoice_sent_email_body' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'booking_request_email_subject.regex' => 'Booking email subject cannot contain HTML tags.',
            'invoice_sent_email_subject.regex' => 'Invoice email subject cannot contain HTML tags.',
        ];
    }
}
