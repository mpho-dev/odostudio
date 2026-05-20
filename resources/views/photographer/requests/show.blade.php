@extends('layouts.app')

@section('title', 'Request Details — Odo Studio')

@section('header', 'Request Details')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Back Link -->
    <a href="{{ route('photographer.requests') }}" class="group relative inline-flex items-center gap-3 glass px-5 py-3 border border-white/10 text-silver text-[0.65rem] tracking-[0.3em] uppercase font-bold hover:border-gold/30 hover:text-gold transition-all duration-500 reveal">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span class="relative z-10">Back to Requests</span>
    </a>

    <!-- Request Details Card -->
    <div class="glass rounded-xl border border-white/10 overflow-hidden reveal" style="animation-delay: 50ms">
        <!-- Header -->
        <div class="p-6 md:p-10 border-b border-white/5">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                <div>
                    <h1 class="font-serif text-3xl text-white mb-4">
                        {{ $bookingRequest->name }} {{ $bookingRequest->surname }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="px-3 py-1 bg-gold/10 text-gold border border-gold/20 rounded-full text-[0.6rem] tracking-widest uppercase font-medium">
                            {{ $bookingRequest->event_type }}
                        </span>
                        <span class="text-xs text-ash/50">{{ $bookingRequest->created_at->format('F d, Y \a\t g:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="p-6 md:p-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <p class="text-[0.55rem] tracking-[0.3em] uppercase text-ash/40 mb-2">Contact Email</p>
                    <a href="mailto:{{ $bookingRequest->email }}" class="text-gold hover:text-gold-lt transition-colors break-all">
                        {{ $bookingRequest->email }}
                    </a>
                </div>
                <div>
                    <p class="text-[0.55rem] tracking-[0.3em] uppercase text-ash/40 mb-2">Phone</p>
                    <a href="tel:{{ $bookingRequest->phone }}" class="text-gold hover:text-gold-lt transition-colors">
                        {{ $bookingRequest->phone }}
                    </a>
                </div>
                <div>
                    <p class="text-[0.55rem] tracking-[0.3em] uppercase text-ash/40 mb-2">Event Date</p>
                    <p class="text-white">{{ $bookingRequest->event_date?->format('F d, Y') ?? 'Not specified' }}</p>
                </div>
                <div>
                    <p class="text-[0.55rem] tracking-[0.3em] uppercase text-ash/40 mb-2">Event Location</p>
                    <p class="text-white">{{ $bookingRequest->event_location ?? 'Not specified' }}</p>
                </div>
            </div>

            @if ($bookingRequest->notes)
                <div class="mb-8">
                    <p class="text-[0.55rem] tracking-[0.3em] uppercase text-ash/40 mb-3">Additional Notes</p>
                    <div class="bg-charcoal/5 dark:bg-black/40 rounded-lg p-6 border border-white/5">
                        <p class="text-ash/80 whitespace-pre-wrap leading-relaxed">{{ $bookingRequest->notes }}</p>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="flex flex-wrap gap-4 pt-8 border-t border-white/5">
                <a href="mailto:{{ $bookingRequest->email }}" class="group relative px-8 py-3 bg-gold text-black text-[0.65rem] tracking-[0.3em] uppercase font-bold hover:bg-white transition-all duration-500 overflow-hidden">
                    <span class="relative z-10">Reply to Client</span>
                    <div class="absolute inset-0 bg-white translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-500"></div>
                </a>
                <a href="{{ route('photographer.requests') }}" class="px-8 py-3 border border-white/10 text-silver text-[0.65rem] tracking-[0.3em] uppercase font-bold hover:border-gold/30 hover:text-gold transition-all duration-500">
                    Back to Requests
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
