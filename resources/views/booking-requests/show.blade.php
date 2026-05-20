@extends('layouts.app')

@section('title', 'Enquiry Details — Odo Studio')

@section('header')
    <div class="flex items-center gap-4">
        <a href="{{ route('photographer.requests') }}" class="text-gold hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <span>Enquiry Profile</span>
    </div>
@endsection

@section('content')
<div class="space-y-8 animate-hero-in">
    <!-- Header Section -->
    <div class="flex justify-between items-start reveal">
        <div>
            <h1 class="font-serif text-4xl text-charcoal dark:text-white leading-tight">
                {{ $bookingRequest->name }} <em>{{ $bookingRequest->surname }}</em>
            </h1>
            <div class="flex items-center gap-4 mt-3">
                <span class="text-[0.6rem] tracking-[0.3em] uppercase text-gold font-bold border border-gold/30 px-3 py-1 bg-gold/5">{{ $bookingRequest->interest_name ?: 'General Enquiry' }}</span>
                <span class="text-xs tracking-[0.2em] uppercase text-ash/60 dark:text-ash/40 italic">Log Date: {{ $bookingRequest->created_at->format('M d, Y') }}</span>
            </div>
        </div>
        <div class="text-right">
            @if($bookingRequest->email_sent_at)
                <div class="inline-block border border-green-500/30 bg-green-500/10 px-4 py-2 text-right">
                    <p class="text-[0.6rem] tracking-[0.3em] uppercase text-green-500 font-bold">Email Dispatched</p>
                    <p class="text-[0.55rem] text-green-500/60 mt-1 italic">{{ $bookingRequest->email_sent_at->format('M d, Y H:i:s') }}</p>
                </div>
            @else
                <div class="inline-block border border-charcoal/20 dark:border-white/10 bg-charcoal/5 dark:bg-white/5 px-4 py-2 text-right">
                    <p class="text-[0.6rem] tracking-[0.3em] uppercase text-ash/60 dark:text-ash/40 font-bold">No Email Sent</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 reveal" style="animation-delay: 100ms;">
        <div class="lg:col-span-2 space-y-8">
            <div class="glass p-10 relative overflow-hidden group border border-charcoal/10 dark:border-white/10 rounded-xl shadow-2xl">
                <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                
                <h3 class="text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold mb-8">Production Particulars</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
                    <div class="space-y-1">
                        <p class="text-[0.55rem] tracking-[0.3em] uppercase text-ash/50 dark:text-ash/40 font-bold">Communications</p>
                        <a href="mailto:{{ $bookingRequest->email }}" class="block text-sm text-charcoal/80 dark:text-silver hover:text-gold transition-colors">{{ $bookingRequest->email }}</a>
                        <a href="tel:{{ $bookingRequest->phone }}" class="block text-sm text-charcoal/80 dark:text-silver hover:text-gold transition-colors">{{ $bookingRequest->phone }}</a>
                    </div>
                    
                    <div class="space-y-1">
                        <p class="text-[0.55rem] tracking-[0.3em] uppercase text-ash/50 dark:text-ash/40 font-bold">Target Date</p>
                        <p class="text-lg font-serif text-charcoal dark:text-white">{{ $bookingRequest->event_date?->format('F d, Y') ?? 'TBD' }}</p>
                    </div>

                    <div class="md:col-span-2 space-y-1">
                        <p class="text-[0.55rem] tracking-[0.3em] uppercase text-ash/50 dark:text-ash/40 font-bold">Location</p>
                        <p class="text-lg font-serif text-charcoal dark:text-white">{{ $bookingRequest->event_location ?? 'TBD' }}</p>
                    </div>
                </div>
            </div>

            @if ($bookingRequest->notes)
            <div class="glass p-10 relative overflow-hidden group border border-charcoal/10 dark:border-white/10 rounded-xl shadow-2xl">
                <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                
                <h3 class="text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold mb-6">Client Directives & Notes</h3>
                
                <div class="pl-6 border-l-2 border-gold/30">
                    <p class="text-sm text-charcoal/80 dark:text-silver italic leading-relaxed whitespace-pre-wrap">{{ $bookingRequest->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-4">
            <a href="mailto:{{ $bookingRequest->email }}" class="flex items-center justify-center w-full bg-gold hover:bg-gold/90 text-black px-6 py-4 text-[0.65rem] tracking-[0.3em] uppercase font-bold transition-all text-center">
                Initiate Contact
            </a>
            
            <a href="{{ route('photographer.requests') }}" class="flex items-center justify-center w-full bg-charcoal/5 dark:bg-white/5 border border-charcoal/10 dark:border-white/10 hover:border-gold hover:text-gold text-charcoal/60 dark:text-silver px-6 py-4 text-[0.65rem] tracking-[0.3em] uppercase font-bold transition-all text-center">
                Return to Archive
            </a>
        </div>
    </div>
</div>
@endsection
