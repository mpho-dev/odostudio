<?php

namespace App\Mail;

use App\Models\BookingRequest;
use App\Models\EmailConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingRequestMail extends Mailable
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
        $templateService = app(\App\Services\EmailTemplateService::class);
        $rendered = $templateService->render('booking_request_admin', $this->bookingRequest);

        $to = EmailConfiguration::getConfig('booking_request_email_to', 'admin@mediaweb.local');
        $from = EmailConfiguration::getConfig('booking_request_email_from', 'noreply@mediaweb.local');

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($from, config('app.name')),
            subject: $rendered['subject'] ?: 'New Booking Request',
            to: $to,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $templateService = app(\App\Services\EmailTemplateService::class);
        $rendered = $templateService->render('booking_request_admin', $this->bookingRequest);

        return new Content(
            view: 'emails.booking-request',
            with: [
                'bookingRequest' => $this->bookingRequest,
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
