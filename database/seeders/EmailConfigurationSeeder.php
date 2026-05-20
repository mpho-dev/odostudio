<?php

namespace Database\Seeders;

use App\Models\EmailConfiguration;
use Illuminate\Database\Seeder;

class EmailConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            'booking_request_email_from' => config('mail.from.address'),
            'booking_request_email_to' => config('mail.from.address'),
            'booking_request_email_subject' => 'New Booking Request: {event_type} on {event_date}',
            'booking_request_email_body' => $this->getDefaultEmailBody(),
        ];

        foreach ($defaults as $key => $value) {
            EmailConfiguration::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }

    /**
     * Get the default email body template.
     */
    private function getDefaultEmailBody(): string
    {
        return '<h2>New Booking Request</h2>
<p>You have received a new booking request with the following details:</p>
<ul>
  <li><strong>Name:</strong> {name} {surname}</li>
  <li><strong>Email:</strong> {email}</li>
  <li><strong>Phone:</strong> {phone}</li>
  <li><strong>Event Type:</strong> {event_type}</li>
  <li><strong>Event Date:</strong> {event_date}</li>
  <li><strong>Event Location:</strong> {event_location}</li>
  <li><strong>Additional Notes:</strong> {notes}</li>
</ul>
<p>Please review this request and follow up with the client at your earliest convenience.</p>';
    }
}
