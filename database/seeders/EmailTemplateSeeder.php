<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'slug' => 'booking_request_admin',
                'name' => 'Booking Request (Admin Notification)',
                'subject' => \App\Models\EmailConfiguration::getConfig('booking_request_email_subject', 'New Booking Request: {event_type} on {event_date}'),
                'body' => \App\Models\EmailConfiguration::getConfig('booking_request_email_body', '<h2>New Booking Request</h2><p>You have received a new booking request with the following details:</p><ul><li><strong>Name:</strong> {name} {surname}</li><li><strong>Email:</strong> {email}</li><li><strong>Phone:</strong> {phone}</li><li><strong>Event Type:</strong> {event_type}</li><li><strong>Event Date:</strong> {event_date}</li><li><strong>Event Location:</strong> {event_location}</li><li><strong>Additional Notes:</strong> {notes}</li></ul><p>Please review this request and follow up with the client at your earliest convenience.</p>'),
                'placeholders' => ['{name}', '{surname}', '{email}', '{phone}', '{event_date}', '{event_type}', '{event_location}', '{notes}'],
            ],
            [
                'slug' => 'visitor_request_confirmation',
                'name' => 'Booking Request (Visitor Confirmation)',
                'subject' => 'We have received your inquiry - Odo Studio',
                'body' => '<p>Dear {name},</p><p>Thank you for reaching out to Odo Studio. We have received your inquiry for <strong>{event_type}</strong> and our team will get back to you shortly.</p><p>In the meantime, feel free to browse our latest portfolio projects.</p><p>Best regards,<br>Odo Studio Team</p>',
                'placeholders' => ['{name}', '{event_type}'],
            ],
            [
                'slug' => 'invoice_sent',
                'name' => 'Invoice Sent',
                'subject' => \App\Models\EmailConfiguration::getConfig('invoice_sent_email_subject', 'Your Invoice from Odo Studio'),
                'body' => \App\Models\EmailConfiguration::getConfig('invoice_sent_email_body', '<p>Dear {client_name},</p><p>Thank you for choosing Odo Studio for your {service_type}. Please find your invoice attached.</p><p><strong>Invoice Details:</strong><br>Invoice Number: {invoice_number}<br>Amount: {amount}<br>Event Date: {event_date}<br>Location: {booking_location}</p><p>If you have any questions about this invoice, please don\'t hesitate to contact us.</p><p>Best regards,<br>Odo Studio Team</p>'),
                'placeholders' => ['{client_name}', '{client_surname}', '{invoice_number}', '{amount}', '{event_date}', '{booking_location}', '{service_type}'],
            ],
            [
                'slug' => 'booking_confirmed',
                'name' => 'Booking Confirmed (Crew Notification)',
                'subject' => 'New Booking Assigned: {client_name} {client_surname}',
                'body' => '<p>Hello,</p><p>A new booking has been assigned to you.</p><p><strong>Client:</strong> {client_name} {client_surname}<br><strong>Event:</strong> {event_type}<br><strong>Date:</strong> {event_date}<br><strong>Location:</strong> {event_location}</p><p>Please check your dashboard for more details.</p>',
                'placeholders' => ['{client_name}', '{client_surname}', '{event_type}', '{event_date}', '{event_location}'],
            ],
        ];

        foreach ($templates as $template) {
            \App\Models\EmailTemplate::updateOrCreate(
                ['slug' => $template['slug']],
                $template
            );
        }
    }
}
