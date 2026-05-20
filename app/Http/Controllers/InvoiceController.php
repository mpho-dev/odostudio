<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Manager creates an invoice for a booking
    public function create(Booking $booking)
    {
        $this->authorize('create', Invoice::class);

        return view('invoices.create', compact('booking'));
    }

    // Store the invoice
    public function store(Request $request, Booking $booking)
    {
        $this->authorize('create', Invoice::class);

        $validated = $request->validate([
            'notes' => 'nullable|string|max:5000',
        ]);

        $invoice = DB::transaction(function () use ($validated, $booking) {
            $totalAmount = $booking->investmentTier->price ?? $booking->rate ?? 0;
            $invoiceNumber = Invoice::generateInvoiceNumber();

            return Invoice::create([
                'booking_id' => $booking->id,
                'invoice_number' => $invoiceNumber,
                'rate' => $booking->rate,
                'total_amount' => $totalAmount,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
                'issued_at' => now(),
            ]);
        });

        \Illuminate\Support\Facades\Log::info("Invoice generated for Booking #{$booking->id}", [
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'booking_id' => $booking->id,
            'total_amount' => $invoice->total_amount,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice created successfully.');
    }

    // View invoice
    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        return view('invoices.show', compact('invoice'));
    }

    // Mark invoice as paid
    public function markPaid(Invoice $invoice)
    {
        $this->authorize('update', $invoice);

        // Validate status transition: only sent invoices can be marked as paid
        if ($invoice->status === 'paid') {
            return back()->with('error', 'Invoice is already marked as paid.');
        }

        if ($invoice->status !== 'sent') {
            return back()->with('error', 'Only sent invoices can be marked as paid.');
        }

        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        \Illuminate\Support\Facades\Log::info("Invoice #{$invoice->invoice_number} marked as paid.", [
            'invoice_id' => $invoice->id,
            'booking_id' => $invoice->booking_id,
            'marked_by' => auth()->id(),
            'total_amount' => $invoice->total_amount,
        ]);

        return back()->with('success', 'Invoice marked as paid.');
    }

    // Mark invoice as sent
    public function markAsSent(Invoice $invoice)
    {
        $this->authorize('update', $invoice);

        // Validate status transition: issued/draft invoices can be sent
        if ($invoice->status === 'sent') {
            return back()->with('error', 'Invoice is already marked as sent.');
        }

        if ($invoice->status === 'paid') {
            return back()->with('error', 'Cannot mark a paid invoice as sent.');
        }

        $invoice->update([
            'status' => 'sent',
            'issued_at' => now(),
        ]);

        // Send email to client with PDF attachment
        $bookingRequest = $invoice->booking?->bookingRequest;
        if ($bookingRequest && $bookingRequest->email) {
            \Illuminate\Support\Facades\Mail::to($bookingRequest->email)->queue(new \App\Mail\InvoiceSentMail($invoice));
        }

        \Illuminate\Support\Facades\Log::info("Invoice #{$invoice->invoice_number} marked as sent and email dispatched.", [
            'invoice_id' => $invoice->id,
            'booking_id' => $invoice->booking_id,
            'marked_by' => auth()->id(),
            'client_email' => $bookingRequest?->email,
        ]);

        return back()->with('success', 'Invoice marked as sent and email dispatched to client.');
    }

    // Download invoice as PDF
    public function pdf(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));

        return $pdf->download('invoice-'.$invoice->invoice_number.'.pdf');
    }

    // List invoices
    public function index()
    {
        $this->authorize('viewAny', Invoice::class);

        $user = auth()->user();
        if ($user->hasRole('admin')) {
            $invoices = Invoice::paginate(10);
        } else {
            $invoices = Invoice::where('created_by', $user->id)->paginate(10);
        }

        return view('invoices.index', compact('invoices'));
    }
}
