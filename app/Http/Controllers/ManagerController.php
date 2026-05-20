<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingRequest;
use App\Models\Invoice;

class ManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:manager']);
    }

    public function dashboard()
    {
        $pendingRequests = BookingRequest::where('status', 'pending')->count();

        $activeBookings = Booking::whereIn('status', ['scheduled', 'confirmed'])->count();

        $draftInvoices = Invoice::where('status', 'draft')->count();

        $totalRevenue = Invoice::where('status', 'paid')->sum('total_amount');

        return view('manager.dashboard', compact(
            'pendingRequests',
            'activeBookings',
            'draftInvoices',
            'totalRevenue'
        ));
    }
}
