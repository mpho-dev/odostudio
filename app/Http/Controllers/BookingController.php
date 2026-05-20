<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmedMail;
use App\Models\Booking;
use App\Models\BookingRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Manager views pending booking requests
    public function indexRequests()
    {
        $this->authorize('viewany', BookingRequest::class);
        $requests = BookingRequest::where('status', 'pending')->paginate(10);
        $crewMembers = User::role('crew')->get();

        return view('manager.requests', compact('requests', 'crewMembers'));
    }

    // Manager creates a booking from a request
    public function create(BookingRequest $bookingRequest)
    {
        $this->authorize('create', Booking::class);

        // Get crew members organized by specialty
        $photographers = User::role('crew')
            ->whereIn('crew_specialty', ['photographer', 'both'])
            ->get();
        $videographers = User::role('crew')
            ->whereIn('crew_specialty', ['videographer', 'both'])
            ->get();

        return view('manager.create-booking', compact('bookingRequest', 'photographers', 'videographers'));
    }

    // Manager stores the booking
    public function store(Request $request, BookingRequest $bookingRequest)
    {
        $this->authorize('create', Booking::class);

        $validated = $request->validate([
            'investment_tier_id' => 'required|exists:investment_tiers,id',
            'event_date' => 'required|date|after:today',
            'location' => 'required|string|max:255',
            'crew_id.photographers' => 'array',
            'crew_id.photographers.*' => 'exists:users,id',
            'crew_id.videographers' => 'array',
            'crew_id.videographers.*' => 'exists:users,id',
        ]);

        // Ensure at least one crew member is assigned
        $photosCount = count($validated['crew_id']['photographers'] ?? []);
        $videosCount = count($validated['crew_id']['videographers'] ?? []);

        if ($photosCount === 0 && $videosCount === 0) {
            return back()->withErrors(['crew' => 'At least one photographer or videographer must be assigned.'])->withInput();
        }

        // Prevent double-booking: check if any crew member is already booked for this date
        $allCrewIds = array_merge(
            $validated['crew_id']['photographers'] ?? [],
            $validated['crew_id']['videographers'] ?? []
        );

        $conflictingBookings = Booking::where('event_date', $validated['event_date'])
            ->whereNotIn('status', ['cancelled'])
            ->whereHas('crew', function ($query) use ($allCrewIds) {
                $query->whereIn('user_id', $allCrewIds);
            })
            ->with('crew')
            ->get();

        if ($conflictingBookings->isNotEmpty()) {
            $conflictingCrew = $conflictingBookings->pluck('crew')->flatten()->filter(function ($crew) use ($allCrewIds) {
                return in_array($crew->id, $allCrewIds);
            })->pluck('name')->unique()->values()->toArray();

            $names = implode(', ', $conflictingCrew);

            return back()->withErrors([
                'crew' => "The following crew members are already booked for {$validated['event_date']}: {$names}",
            ])->withInput();
        }

        // Set the primary photographer for backward compatibility
        $primaryPhotographerId = $validated['crew_id']['photographers'][0] ?? $validated['crew_id']['videographers'][0];

        $booking = Booking::create(array_merge(
            [
                'booking_request_id' => $bookingRequest->id,
                'photographer_id' => $primaryPhotographerId,
                'created_by' => auth()->id(),
            ],
            $validated
        ));

        // Assign all crew members
        $crewToAssign = [];
        foreach ($validated['crew_id']['photographers'] ?? [] as $photographerId) {
            $crewToAssign[$photographerId] = ['role' => 'photographer'];
        }
        foreach ($validated['crew_id']['videographers'] ?? [] as $videographerId) {
            if (! isset($crewToAssign[$videographerId])) {
                $crewToAssign[$videographerId] = ['role' => 'videographer'];
            } else {
                // If user is already added as photographer, skip adding as videographer
                // or update to show both roles (varies by business logic)
            }
        }

        $booking->crew()->sync($crewToAssign);

        $bookingRequest->update(['status' => 'confirmed']);

        \Illuminate\Support\Facades\Log::info("Booking generated for Request #{$bookingRequest->id}", [
            'booking_id' => $booking->id,
            'booking_request_id' => $bookingRequest->id,
            'manager_id' => auth()->id(),
            'crew_assigned' => $crewToAssign,
            'location' => $booking->location,
        ]);

        // Send confirmation to all crew members
        $allCrewMembers = $booking->crew;
        foreach ($allCrewMembers as $crewMember) {
            Mail::to($crewMember)->queue(new BookingConfirmedMail($booking));
        }

        return redirect()->route('bookings.index')->with('success', 'Booking created successfully with crew assignment.');
    }

    // Manager/Artist views all bookings
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('crew')) {
            // Get bookings where user is assigned as crew or is the primary photographer
            $bookings = Booking::withTrashed()
                ->where(function ($query) use ($user) {
                    $query->where('photographer_id', $user->id)
                        ->orWhereHas('crew', function ($q) use ($user) {
                            $q->where('user_id', $user->id);
                        });
                })
                ->with('crew', 'bookingRequest')
                ->distinct()
                ->paginate(10);
        } else {
            $bookings = Booking::withTrashed()
                ->with('crew', 'bookingRequest')
                ->paginate(10);
        }

        return view('bookings.index', compact('bookings'));
    }

    // Photographer Calendar View
    public function calendar()
    {
        return view('photographer.calendar');
    }

    // Photographer Calendar JSON endpoint
    public function calendarEvents()
    {
        $user = auth()->user();

        $bookings = Booking::where(function ($query) use ($user) {
            $query->where('photographer_id', $user->id)
                ->orWhereHas('crew', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
        })
            ->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->with('bookingRequest')
            ->get();

        $events = $bookings->map(function ($booking) {
            $color = '#4f46e5'; // Indigo default
            if ($booking->status === 'completed') {
                $color = '#10b981';
            } // Green
            if ($booking->status === 'cancelled') {
                $color = '#ef4444';
            } // Red

            return [
                'id' => $booking->id,
                'title' => $booking->bookingRequest->name.' - '.$booking->location,
                'start' => $booking->event_date->toIso8601String(),
                'allDay' => false,
                'color' => $color,
                'extendedProps' => [
                    'location' => $booking->location,
                    'status' => $booking->status,
                    'notes' => $booking->bookingRequest->notes,
                ],
            ];
        });

        return response()->json($events);
    }

    // Cancel a booking (status change only, no soft delete)
    public function cancel(Booking $booking)
    {
        $this->authorize('delete', $booking);
        $booking->cancel();

        \Illuminate\Support\Facades\Log::info("Booking #{$booking->id} cancelled.", [
            'booking_id' => $booking->id,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Booking cancelled.');
    }

    // Mark a booking as confirmed
    public function confirm(Booking $booking)
    {
        $this->authorize('update', $booking);

        // Validate status transition: only pending bookings can be confirmed
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be confirmed.');
        }

        $booking->update(['status' => 'confirmed']);

        \Illuminate\Support\Facades\Log::info("Booking #{$booking->id} manually confirmed.", [
            'booking_id' => $booking->id,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Booking manually confirmed.');
    }

    // Mark a booking as completed
    public function complete(Booking $booking)
    {
        $this->authorize('update', $booking);

        // Validate status transition: only confirmed bookings can be completed
        if (! in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Only pending or confirmed bookings can be marked as completed.');
        }

        $booking->update(['status' => 'completed']);

        \Illuminate\Support\Facades\Log::info("Booking #{$booking->id} marked as completed.", [
            'booking_id' => $booking->id,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Booking marked as completed.');
    }

    // Restore a soft-deleted booking
    public function restore($id)
    {
        $booking = Booking::withTrashed()->findOrFail($id);
        $this->authorize('restore', $booking);
        $booking->restore();
        // Restore to pending status for re-processing
        $booking->update(['status' => 'pending']);

        return back()->with('success', 'Booking restored successfully.');
    }

    // Restore a cancelled booking back to scheduled status
    public function restoreFromCancellation(Booking $booking)
    {
        $this->authorize('restore', $booking);

        if ($booking->status !== 'cancelled') {
            return back()->with('error', 'Only cancelled bookings can be restored.');
        }

        $booking->restoreFromCancellation();

        \Illuminate\Support\Facades\Log::info("Booking #{$booking->id} restored from cancellation.", [
            'booking_id' => $booking->id,
            'restored_by' => auth()->id(),
        ]);

        return back()->with('success', 'Cancelled booking restored successfully.');
    }

    // Force delete a booking
    public function forceDelete($id)
    {
        $booking = Booking::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $booking);
        $booking->forceDelete();

        return back()->with('success', 'Booking permanently deleted.');
    }
}
