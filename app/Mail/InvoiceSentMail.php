<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceSentMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Invoice $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function envelope(): Envelope
    {
        $templateService = app(\App\Services\EmailTemplateService::class);
        $rendered = $templateService->render('invoice_sent', $this->invoice);

        return new Envelope(
            subject: $rendered['subject'] ?: 'Your Invoice from Odo Studio',
        );
    }

    public function content(): Content
    {
        $templateService = app(\App\Services\EmailTemplateService::class);
        $rendered = $templateService->render('invoice_sent', $this->invoice);

        return new Content(
            view: 'emails.invoice-sent',
            with: [
                'emailBody' => $rendered['body'],
                'invoice' => $this->invoice,
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
        $attachments = [];

        // Attach PDF if exists
        if ($this->invoice->pdf_path && file_exists(storage_path('app/public/' . $this->invoice->pdf_path))) {
            $attachments[] = Attachment::fromPath(storage_path('app/public/' . $this->invoice->pdf_path))
                ->as('Invoice-' . $this->invoice->invoice_number . '.pdf')
                ->withMime('application/pdf');
        }

        return $attachments;
    }

    /**
     * Get default email body if no template configured.
     */
    private function getDefaultBody(): string
    {
        return '<p>Dear {client_name},</p>

<p>Thank you for choosing Odo Studio for your {service_type}. Please find your invoice attached.</p>

<p><strong>Invoice Details:</strong><br>
Invoice Number: {invoice_number}<br>
Amount: {amount}<br>
Event Date: {event_date}<br>
Location: {booking_location}</p>

<p>If you have any questions about this invoice, please don\'t hesitate to contact us.</p>

<p>Best regards,<br>
Odo Studio Team</p>';
    }
}
