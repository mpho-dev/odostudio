@extends('layouts.app')

@section('title', 'Invoice Detail — Odo Studio')

@section('header', 'Invoice Detail')

@section('content')
<div class="max-w-4xl mx-auto space-y-12 animate-hero-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 reveal">
        <div>
            <h1 class="font-serif text-3xl text-charcoal dark:text-white leading-tight">Invoice <em>#{{ $invoice->invoice_number }}</em></h1>
            <p class="text-xs tracking-[0.3em] uppercase text-ash/90 dark:text-ash/60 mt-2 italic">Payment tracking for production</p>
        </div>
        <div class="flex gap-4">
            @if(auth()->user()->hasRole('manager') && $invoice->status !== 'paid')
                <form action="{{ route('invoices.pay', $invoice) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-gold text-black px-6 py-3 text-[0.6rem] tracking-[0.3em] uppercase font-bold hover:bg-white transition-all shadow-lg shadow-gold/10">
                        Mark as Paid
                    </button>
                </form>
            @endif
            <a href="{{ route('invoices.pdf', $invoice) }}" class="bg-charcoal/5 dark:bg-white/5 border border-charcoal/10 dark:border-white/10 text-charcoal/70 dark:text-silver px-6 py-3 text-[0.6rem] tracking-[0.3em] uppercase font-bold hover:bg-gold/10 hover:text-gold hover:border-gold/30 transition-all">
                Download PDF
            </a>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 reveal">
        <div class="glass p-8 rounded-xl border border-charcoal/10 dark:border-white/10 space-y-6 shadow-2xl relative overflow-hidden group">
            <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <h3 class="text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold border-b border-charcoal/10 dark:border-white/10 pb-4">Invoice Metadata</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-ash/60 dark:text-ash/40 uppercase tracking-widest">Payment Status</span>
                    <span class="px-2 py-0.5 rounded-full text-[0.5rem] tracking-widest uppercase font-bold 
                        @if($invoice->status === 'paid') bg-green-500/10 text-green-500 
                        @elseif($invoice->status === 'issued') bg-gold/10 text-gold
                        @else bg-charcoal/5 dark:bg-white/5 text-charcoal/60 dark:text-silver @endif">
                        {{ $invoice->status }}
                    </span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-ash/60 dark:text-ash/40 uppercase tracking-widest">Issued Date</span>
                    <span class="text-charcoal/80 dark:text-silver italic font-serif">{{ $invoice->issued_at ? $invoice->issued_at->format('M d, Y') : 'N/A' }}</span>
                </div>
                @if($invoice->paid_at)
                <div class="flex justify-between items-center text-xs">
                    <span class="text-ash/60 dark:text-ash/40 uppercase tracking-widest">Paid Date</span>
                    <span class="text-charcoal/80 dark:text-silver italic font-serif">{{ $invoice->paid_at->format('M d, Y') }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="glass p-8 rounded-xl border border-charcoal/10 dark:border-white/10 space-y-6 shadow-2xl relative overflow-hidden group">
            <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <h3 class="text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold border-b border-charcoal/10 dark:border-white/10 pb-4">Client Details</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-ash/60 dark:text-ash/40 uppercase tracking-widest">Name</span>
                    <span class="text-charcoal dark:text-white font-serif text-sm italic">{{ $invoice->booking->bookingRequest->name }} {{ $invoice->booking->bookingRequest->surname }}</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-ash/60 dark:text-ash/40 uppercase tracking-widest">Contact</span>
                    <span class="text-charcoal/80 dark:text-silver italic">{{ $invoice->booking->bookingRequest->email }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Table -->
    <div class="glass rounded-xl border border-charcoal/10 dark:border-white/10 overflow-hidden reveal shadow-2xl relative overflow-hidden group">
        <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
        <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
        <table class="min-w-full divide-y divide-charcoal/10 dark:divide-white/5">
            <thead>
                <tr class="bg-charcoal/5 dark:bg-white/5">
                    <th class="px-8 py-5 text-left text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Shoot Details</th>
                    <th class="px-8 py-5 text-right text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Rate</th>
                    <th class="px-8 py-5 text-right text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-charcoal/10 dark:divide-white/5 animate-hero-in">
                <tr class="hover:bg-gold/5 transition-colors group">
                    <td class="px-8 py-8">
                        <p class="font-serif text-lg text-charcoal dark:text-white leading-tight group-hover:text-gold transition-colors">
                            {{ $invoice->booking->location }}
                        </p>
                        <p class="text-[0.6rem] tracking-widest uppercase text-ash/60 dark:text-ash/40 mt-1 italic">Capture Date: {{ $invoice->booking->event_date->format('M d, Y') }}</p>
                    </td>
                    <td class="px-8 py-8 text-right text-charcoal/70 dark:text-silver italic font-serif">R{{ number_format($invoice->rate, 2) }}</td>
                    <td class="px-8 py-8 text-right text-charcoal dark:text-white font-serif text-xl italic">R{{ number_format($invoice->total_amount, 2) }}</td>
                </tr>
            </tbody>
            <tfoot class="bg-charcoal/5 dark:bg-white/5">
                <tr>
                    <td colspan="3" class="px-8 py-6 text-right text-[0.6rem] tracking-[0.4em] uppercase text-charcoal/80 dark:text-silver font-bold">Total Due</td>
                    <td class="px-8 py-6 text-right text-2xl font-serif text-gold italic">R{{ number_format($invoice->total_amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    @if ($invoice->notes)
        <div class="glass p-8 rounded-xl border border-l-4 border-gold/40 dark:border-gold/20 reveal shadow-2xl relative overflow-hidden group">
            <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <h3 class="text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold mb-4">Production Addendum</h3>
            <p class="font-serif text-lg text-charcoal/80 dark:text-silver/80 italic leading-relaxed">{{ $invoice->notes }}</p>
        </div>
    @endif

    <div class="pt-12 border-t border-charcoal/10 dark:border-white/10 flex justify-between items-center reveal">
        <a href="{{ route('invoices.index') }}" class="group flex items-center gap-4 text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-ash/40 hover:text-gold transition-all italic">
            <span class="transition-transform group-hover:-translate-x-1">←</span> Return to Archive
        </a>
        <p class="text-[0.55rem] tracking-[0.2em] uppercase text-ash/40 dark:text-ash/30 italic">Perspective Settlement Archive — Odo Studio v4.0</p>
    </div>
</div>
@endsection
