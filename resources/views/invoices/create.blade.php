@extends('layouts.app')

@section('title', 'Draft Invoice — Odo Studio')

@section('header', 'Draft Invoice')

@section('content')
<div class="max-w-3xl mx-auto space-y-12 animate-hero-in">
    <!-- Header -->
    <div class="reveal">
        <h1 class="font-serif text-3xl text-charcoal dark:text-white leading-tight">Draft <em>Retainer</em></h1>
        <p class="text-xs tracking-[0.3em] uppercase text-ash/60 dark:text-ash/60 mt-2 italic">Set payment terms for the shoot</p>
    </div>

    <!-- Booking Context -->
    <div class="glass p-8 rounded-xl border border-charcoal/10 dark:border-white/10 reveal shadow-2xl relative overflow-hidden group">
        <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
        <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
        <div class="flex items-center gap-6">
            <div class="w-12 h-12 bg-gold/10 border border-gold/20 flex items-center justify-center text-gold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-[0.55rem] tracking-[0.3em] uppercase text-ash/60 dark:text-ash/40 mb-1 font-bold">Shoot Details</p>
                <p class="text-charcoal dark:text-white font-serif text-lg italic">
                    {{ $booking->bookingRequest->name }} {{ $booking->bookingRequest->surname }} — {{ $booking->event_date->format('M d, Y') }}
                </p>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-6 bg-red-500/5 border border-red-500/20 text-red-500 rounded-xl reveal">
            <p class="text-[0.6rem] tracking-[0.3em] uppercase font-bold mb-3">Validation Errors</p>
            <ul class="text-xs space-y-1 italic">
                @foreach ($errors->all() as $error)
                    <li>— {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="glass p-12 rounded-xl border border-charcoal/10 dark:border-white/10 reveal">
        <form method="POST" action="{{ route('invoices.store', $booking) }}" class="space-y-10">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Tier Price Display -->
                <div class="space-y-4">
                    <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold italic">Collection & Rate</label>
                    <div class="bg-charcoal/5 dark:bg-white/5 border border-charcoal/10 dark:border-white/10 text-charcoal dark:text-white px-6 py-4 font-serif text-xl italic rounded">
                        @if ($booking->investmentTier)
                            <span class="text-gold">{{ $booking->investmentTier->name }}</span> — R{{ number_format($booking->investmentTier->price, 2) }}
                        @else
                            <span class="text-red-500">No tier assigned</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="space-y-4">
                <label for="notes" class="block text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold italic">Invoice Notes</label>
                <textarea id="notes" name="notes" rows="4" 
                    class="w-full bg-charcoal/5 dark:bg-white/5 border border-charcoal/10 dark:border-white/10 text-charcoal dark:text-white px-6 py-6 font-serif italic focus:border-gold transition-all outline-none placeholder:text-ash/20" 
                    placeholder="Add payment instructions or breakdown...">{{ old('notes') }}</textarea>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-between pt-10 border-t border-charcoal/10 dark:border-white/10">
                <a href="{{ route('bookings.index') }}" class="text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 hover:text-charcoal dark:hover:text-white transition-all italic">
                    ← Abort
                </a>
                <button type="submit" class="bg-gold text-black px-10 py-4 text-[0.65rem] tracking-[0.3em] uppercase font-bold hover:bg-white transition-all shadow-xl shadow-gold/20">
                    Issue Invoice
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
