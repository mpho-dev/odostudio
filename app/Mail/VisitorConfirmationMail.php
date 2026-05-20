<?php

namespace App\Mail;

use App\Models\BookingRequest;
use App\Services\EmailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VisitorConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public BookingRequest $bookingRequest)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $templateService = app(EmailTemplateService::class);
        $rendered = $templateService->render('visitor_request_confirmation', $this->bookingRequest);

        return new Envelope(
            subject: $rendered['subject'] ?: 'We have received your inquiry - Odo Studio',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $templateService = app(EmailTemplateService::class);
        $rendered = $templateService->render('visitor_request_confirmation', $this->bookingRequest);

        return new Content(
            view: 'emails.generic-template',
            with: [
                'emailBody' => $rendered['body'],
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
