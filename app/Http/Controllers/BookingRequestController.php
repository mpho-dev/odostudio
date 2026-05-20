<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignCrewRequest;
use App\Http\Requests\StoreBookingRequestRequest;
use App\Jobs\SendBookingRequestEmail;
use App\Mail\RequestReceivedMail;
use App\Models\BookingRequest;
use App\Models\InvestmentTier;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BookingRequestController extends Controller
{
    public function showForm()
    {
        $services = Service::orderBy('order')->get();
        $investmentTiers = InvestmentTier::orderBy('order')->get();
        $settings = \App\Models\SiteSetting::all()->pluck('value', 'key')->toArray();

        return view('contact-form', compact('services', 'investmentTiers', 'settings'));
    }

    public function store(StoreBookingRequestRequest $request)
    {
        $validated = $request->validated();

        $serviceId = null;
        $tierId = null;
        $eventType = null;

        if (! empty($validated['event_type'])) {
            if (str_starts_with($validated['event_type'], 'service_')) {
                $serviceId = str_replace('service_', '', $validated['event_type']);
            } elseif (str_starts_with($validated['event_type'], 'tier_')) {
                $tierId = str_replace('tier_', '', $validated['event_type']);
            } else {
                $eventType = $validated['event_type'];
            }
        }

        $bookingRequest = BookingRequest::create([
            'name' => $validated['name'],
            'surname' => $validated['surname'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'event_date' => $validated['event_date'] ?? null,
            'event_type' => $eventType,
            'service_id' => $serviceId,
            'investment_tier_id' => $tierId,
            'event_location' => $validated['event_location'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Dispatch email job asynchronously
        SendBookingRequestEmail::dispatch($bookingRequest);

        // Send confirmation email to the visitor
        Mail::to($bookingRequest->email)->queue(new \App\Mail\VisitorConfirmationMail($bookingRequest));

        // Notify photographers with matching interests
        $this->notifyPhotographers($bookingRequest);

        \Illuminate\Support\Facades\Log::info('New Booking Request logged via frontend.', [
            'booking_request_id' => $bookingRequest->id,
            'email' => $bookingRequest->email,
        ]);

        return back()->with('success', 'Thank you for your booking request. We will contact you soon.');
    }

    /**
     * Show booking requests (manager) or photographer-relevant requests.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('crew')) {
            // Crew members see only requests they're assigned to as crew
            $bookingRequests = BookingRequest::whereHas('crew', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
                ->orWhereHas('photographers', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->orWhereHas('videographers', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->orderByDesc('created_at')
                ->paginate(15);
        } else {
            // Managers see all requests
            $bookingRequests = BookingRequest::orderByDesc('created_at')->paginate(15);
        }

        return view('booking-requests.index', compact('bookingRequests'));
    }

    /**
     * Show a single booking request.
     */
    public function show(BookingRequest $bookingRequest)
    {
        $this->authorize('view', $bookingRequest);

        return view('booking-requests.show', compact('bookingRequest'));
    }

    /**
     * Assign crew members to a booking request.
     */
    public function assignCrew(AssignCrewRequest $request, BookingRequest $bookingRequest)
    {
        $this->authorize('update', $bookingRequest);

        $validated = $request->validated();

        $user = User::findOrFail($validated['user_id']);

        // Verify user has crew role
        if (! $user->hasRole('crew')) {
            return back()->withErrors(['user_id' => 'Selected user must be a crew member.']);
        }

        // Check if already assigned
        if ($bookingRequest->crew()->where('user_id', $user->id)->exists()) {
            return back()->with('info', 'Crew member already assigned to this request.');
        }

        $bookingRequest->crew()->attach($user->id, ['role' => $validated['role']]);

        \Illuminate\Support\Facades\Log::info('Crew assigned to booking request.', [
            'booking_request_id' => $bookingRequest->id,
            'user_id' => $user->id,
            'role' => $validated['role'],
            'assigned_by' => auth()->id(),
        ]);

        return back()->with('success', 'Crew member assigned successfully.');
    }

    /**
     * Remove a crew member from a booking request.
     */
    public function removeCrew(BookingRequest $bookingRequest, User $user)
    {
        $this->authorize('update', $bookingRequest);

        $bookingRequest->crew()->detach($user->id);

        \Illuminate\Support\Facades\Log::info('Crew removed from booking request.', [
            'booking_request_id' => $bookingRequest->id,
            'user_id' => $user->id,
            'removed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Crew member removed successfully.');
    }

    /**
     * Notify photographers about new booking requests matching their interests.
     */
    private function notifyPhotographers(BookingRequest $bookingRequest): void
    {
        // Get all crew members with notification preference enabled
        $crew = User::role('crew')
            ->whereHas('notificationPreference', function ($query) {
                $query->where('notify_new_requests', true);
            })
            ->get();

        foreach ($crew as $crewMember) {
            Mail::to($crewMember)->queue(new RequestReceivedMail($bookingRequest));
        }
    }
}
