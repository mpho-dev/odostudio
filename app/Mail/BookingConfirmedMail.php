<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Booking $booking)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $templateService = app(\App\Services\EmailTemplateService::class);
        $rendered = $templateService->render('booking_confirmed', $this->booking);

        return new Envelope(
            subject: $rendered['subject'] ?: 'New Booking Assigned: '.$this->booking->bookingRequest->name.' '.$this->booking->bookingRequest->surname,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $templateService = app(\App\Services\EmailTemplateService::class);
        $rendered = $templateService->render('booking_confirmed', $this->booking);

        return new Content(
            view: 'emails.booking-confirmed',
            with: [
                'booking' => $this->booking,
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
