<?php

namespace App\Http\Controllers;

use App\Models\NotificationPreference;
use Illuminate\Http\Request;

class NotificationPreferenceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:crew');
    }

    /**
     * Show the notification preferences edit form.
     */
    public function edit()
    {
        $user = auth()->user();
        $preference = $user->notificationPreference ?? NotificationPreference::create([
            'user_id' => $user->id,
            'notify_new_requests' => true,
        ]);

        return view('photographer.notification-preferences', compact('preference'));
    }

    /**
     * Update the user's notification preferences.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'notify_new_requests' => 'required|boolean',
        ]);

        $user = auth()->user();
        $preference = $user->notificationPreference ?? NotificationPreference::create([
            'user_id' => $user->id,
        ]);

        $preference->update($validated);

        return back()->with('success', 'Notification preferences updated successfully.');
    }
}
