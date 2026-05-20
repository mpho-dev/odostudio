<?php

namespace App\Http\Controllers;

use App\Mail\BookingRequestMail;
use App\Models\BookingRequest;
use App\Models\EmailConfiguration;
use App\Models\EmailTemplate;
use App\Services\EmailTemplateService;
use App\Services\HtmlPurifier as PurifierService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function __construct(protected EmailTemplateService $templateService)
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Show email configuration form.
     */
    public function editEmailConfig()
    {
        $configs = EmailConfiguration::all()->pluck('value', 'key')->toArray();
        $templates = EmailTemplate::all();

        // System settings (From/To addresses)
        $emailFrom = $configs['booking_request_email_from'] ?? 'noreply@mediaweb.local';
        $emailTo = $configs['booking_request_email_to'] ?? 'admin@mediaweb.local';
        $invoiceEmailFrom = $configs['invoice_sent_email_from'] ?? 'billing@mediaweb.local';

        return view('admin.email-config', compact(
            'emailFrom', 'emailTo', 'invoiceEmailFrom', 'templates'
        ));
    }

    /**
     * Update email configuration.
     */
    public function updateEmailConfig(Request $request)
    {
        // Update system configs (From/To)
        if ($request->has('booking_request_email_from')) {
            EmailConfiguration::setConfig('booking_request_email_from', $request->input('booking_request_email_from'));
        }
        if ($request->has('booking_request_email_to')) {
            EmailConfiguration::setConfig('booking_request_email_to', $request->input('booking_request_email_to'));
        }
        if ($request->has('invoice_sent_email_from')) {
            EmailConfiguration::setConfig('invoice_sent_email_from', $request->input('invoice_sent_email_from'));
        }

        // Update templates
        if ($request->has('templates')) {
            foreach ($request->input('templates') as $slug => $data) {
                $template = EmailTemplate::where('slug', $slug)->first();
                if ($template) {
                    $template->update([
                        'subject' => $data['subject'],
                        'body' => PurifierService::clean($data['body']),
                    ]);
                }
            }
        }

        return back()->with('success', 'Email configuration and templates updated successfully.');
    }

    /**
     * Preview an email template with sample data.
     */
    public function previewEmailConfig(Request $request, string $slug)
    {
        $sampleRequest = new BookingRequest([
            'name' => 'Thabo',
            'surname' => 'Mokoena',
            'email' => 'thabo.mokoena@mediaweb.local',
            'phone' => '+27 82 555 0192',
            'event_type' => 'Cinematic Brand Film',
            'event_date' => now()->addMonths(3)->toDateString(),
            'event_location' => 'Sandton, Johannesburg',
            'notes' => 'We are looking to capture a high-end cinematic brand story for our new launch in Sandton.',
        ]);

        $rendered = $this->templateService->render($slug, $sampleRequest);

        $html = view('emails.booking-request', [
            'bookingRequest' => $sampleRequest,
            'emailBody' => $rendered['body'],
        ])->render();

        return response()->json([
            'subject' => $rendered['subject'],
            'body' => $rendered['body'],
            'html' => $html,
        ]);
    }

    /**
     * Send a test email with current configuration.
     */
    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
            'template_slug' => 'required|string|exists:email_templates,slug',
        ]);

        $testEmail = $request->input('test_email');
        $slug = $request->input('template_slug');

        $sampleRequest = new BookingRequest([
            'name' => 'Thabo',
            'surname' => 'Mokoena',
            'email' => 'thabo.mokoena@mediaweb.local',
            'phone' => '+27 82 555 0192',
            'event_type' => 'Cinematic Brand Film',
            'event_date' => now()->addMonths(3)->toDateString(),
            'event_location' => 'Sandton, Johannesburg',
            'notes' => 'Test email for production readiness.',
        ]);

        try {
            $mailable = match($slug) {
                'booking_request_admin' => new \App\Mail\BookingRequestMail($sampleRequest),
                'visitor_request_confirmation' => new \App\Mail\VisitorConfirmationMail($sampleRequest),
                default => throw new \Exception("Mailable for slug {$slug} not configured for testing."),
            };

            Mail::to($testEmail)->queue($mailable);

            return back()->with('success', 'Cinematic test dispatch successful. Archive verification complete.');
        } catch (\Exception $e) {
            \Log::error('Cinematic test dispatch failed: '.$e->getMessage());
            return back()->with('error', "Failed to send test email: {$e->getMessage()}");
        }
    }
}
