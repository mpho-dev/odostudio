<?php

namespace App\Jobs;

use App\Mail\BookingRequestMail;
use App\Models\BookingRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendBookingRequestEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public BookingRequest $bookingRequest)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::send(new BookingRequestMail($this->bookingRequest));
        $this->bookingRequest->update(['email_sent_at' => now()]);
    }
}
