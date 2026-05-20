<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\EquipmentCheckout;
use App\Models\EquipmentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EquipmentCheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function assign(Booking $booking)
    {
        $this->authorize('update', $booking);

        $booking->load(['crew', 'investmentTier']);

        $assigned = $booking->equipmentCheckouts()
            ->with('equipmentItem.category')
            ->get();

        $available = EquipmentItem::availableFor($booking->event_date)
            ->with(['category', 'primaryImage'])
            ->orderBy('category_id')
            ->orderBy('name')
            ->get()
            ->groupBy('category.name');

        $suggested = $booking->suggestEquipment();

        return view('manager.equipment.assign', compact(
            'booking', 'assigned', 'available', 'suggested'
        ));
    }

    public function store(Request $request, Booking $booking)
    {
        $this->authorize('update', $booking);

        $validated = $request->validate([
            'equipment_item_id' => [
                'required',
                'exists:equipment_items,id',
                function ($attribute, $value, $fail) use ($request, $booking) {
                    $item = EquipmentItem::find($value);
                    $user = \App\Models\User::find($request->input('user_id'));

                    // Check if user is assigned to this booking
                    if (! $booking->crew()->where('user_id', $user->id)->exists()) {
                        $fail("{$user->name} is not assigned to this booking and cannot be assigned equipment.");
                    }

                    // For photography equipment, ensure user has photographer capability
                    if (str_contains(strtolower($item->category->name ?? ''), 'photo') ||
                        str_contains(strtolower($item->name ?? ''), 'camera') ||
                        str_contains(strtolower($item->name ?? ''), 'lens')) {
                        if ($user->crew_specialty !== 'photographer' && $user->crew_specialty !== 'both') {
                            $fail('Photography equipment can only be assigned to photographers or dual-role crew members.');
                        }
                    }

                    // For video equipment, ensure user has videographer capability
                    if (str_contains(strtolower($item->category->name ?? ''), 'video') ||
                        str_contains(strtolower($item->name ?? ''), 'video') ||
                        str_contains(strtolower($item->name ?? ''), 'camera') && str_contains(strtolower($item->name ?? ''), 'video')) {
                        if ($user->crew_specialty !== 'videographer' && $user->crew_specialty !== 'both') {
                            $fail('Video equipment can only be assigned to videographers or dual-role crew members.');
                        }
                    }
                },
            ],
            'user_id' => 'required|exists:users,id',
            'checkout_notes' => 'nullable|string',
        ]);

        $item = EquipmentItem::findOrFail($validated['equipment_item_id']);

        // Use database transaction with locking to prevent race conditions
        try {
            DB::transaction(function () use ($validated, $booking, $item) {
                // Lock the equipment item row to prevent concurrent checkouts
                $lockedItem = EquipmentItem::where('id', $item->id)
                    ->lockForUpdate()
                    ->first();

                if (! $lockedItem->isAvailableFor($booking->event_date)) {
                    throw new \Exception('Equipment not available for this date.');
                }

                EquipmentCheckout::create([
                    'booking_id' => $booking->id,
                    'user_id' => $validated['user_id'],
                    'equipment_item_id' => $lockedItem->id,
                    'checked_out_at' => now(),
                    'expected_return_at' => $booking->event_date->addDay(),
                    'condition_out' => $lockedItem->condition,
                    'checkout_notes' => $validated['checkout_notes'],
                    'status' => 'active',
                ]);

                $lockedItem->update(['status' => 'checked_out']);
            });

            Log::info("Equipment {$item->name} assigned to booking #{$booking->id}");

            return back()->with('success', "{$item->name} assigned to booking.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function remove(EquipmentCheckout $checkout)
    {
        $this->authorize('update', $checkout->booking);

        if ($checkout->status !== 'active') {
            return back()->with('error', 'Cannot remove already returned equipment.');
        }

        $item = $checkout->equipmentItem;
        $checkout->delete();

        $item->update(['status' => 'available']);

        Log::info("Equipment {$item->name} removed from booking #{$checkout->booking_id}");

        return back()->with('success', "{$item->name} removed from booking.");
    }

    public function checkIn(Request $request, EquipmentCheckout $checkout)
    {
        $validated = $request->validate([
            'condition_in' => 'required|in:excellent,good,fair,poor,damaged',
            'return_notes' => 'nullable|string',
        ]);

        $checkout->checkIn($validated['condition_in'], $validated['return_notes'] ?? null);

        if ($validated['condition_in'] !== $checkout->condition_out) {
            Log::info("Equipment {$checkout->equipmentItem->name} returned with different condition: {$checkout->condition_out} -> {$validated['condition_in']}");
        }

        return back()->with('success', 'Equipment checked in successfully.');
    }

    public function myGear()
    {
        $user = auth()->user();

        $current = $user->currentEquipment()
            ->with(['category', 'checkouts' => function ($q) {
                $q->where('user_id', auth()->id())->where('status', 'active');
            }])
            ->get();

        $history = $user->equipmentHistory()
            ->with('category')
            ->limit(20)
            ->get();

        return view('photographer.equipment.my-gear', compact('current', 'history'));
    }

    public function overdue()
    {
        $this->authorize('admin');

        $overdue = EquipmentCheckout::with(['equipmentItem', 'user', 'booking'])
            ->where('status', 'active')
            ->where('expected_return_at', '<', now())
            ->orderBy('expected_return_at')
            ->paginate(20);

        return view('admin.equipment.overdue', compact('overdue'));
    }
}
