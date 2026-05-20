<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->user()->fill($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($request->user()->id)],
        ]));

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        if ($user->hasRole('admin')) {
            return Redirect::route('profile.edit')->with('error', 'Administrators cannot delete their own account. Contact another admin to manage admin accounts.');
        }

        // Check if user has created invoices or bookings that would become orphaned
        $createdInvoices = \App\Models\Invoice::where('created_by', $user->id)->count();
        $createdBookings = \App\Models\Booking::where('created_by', $user->id)->count();

        if ($createdInvoices > 0 || $createdBookings > 0) {
            return Redirect::route('profile.edit')->with('error', 'Cannot delete account with existing bookings or invoices created. Please contact an administrator.');
        }

        Auth::logout();

        $user->delete(); // Soft delete

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
