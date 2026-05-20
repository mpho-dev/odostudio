@extends('layouts.app')

@section('title', 'Booking Requests — Odo Studio')

@section('header', 'Booking Requests')

@section('content')
<div class="space-y-12 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-end gap-6 reveal">
        <div>
            <div class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
                <span>Field Intelligence</span>
                <div class="h-px w-16 bg-gold/30"></div>
            </div>
            <h1 class="font-serif text-3xl text-charcoal dark:text-white leading-tight">Booking <em>Requests</em></h1>
            <p class="text-xs tracking-[0.3em] uppercase text-ash/60 dark:text-ash/60 mt-2 italic">Incoming casting enquiries and inquiry pipeline</p>
        </div>
        <a href="{{ route('photographer.preferences') }}" class="group relative px-6 py-3 glass border border-white/10 text-silver text-[0.65rem] tracking-[0.3em] uppercase font-bold hover:border-gold/30 hover:text-gold transition-all duration-500">
            <span class="relative z-10">Notification Settings</span>
        </a>
    </div>

    @if ($bookingRequests->isEmpty())
        <!-- Empty State -->
        <div class="reveal">
            <div class="glass rounded-xl border border-white/10 p-16 text-center">
                <div class="w-16 h-16 rounded-lg bg-gold/10 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-gold/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="font-serif text-xl text-white mb-2">No Requests Yet</h3>
                <p class="text-sm text-ash/60">New booking enquiries will appear here once received.</p>
            </div>
        </div>
    @else
        <!-- Request Cards -->
        <div class="space-y-4 reveal" style="animation-delay: 50ms">
            @foreach ($bookingRequests as $request)
                <a href="{{ route('photographer.requests.show', $request) }}" class="block glass rounded-xl border border-white/10 overflow-hidden hover:border-gold/30 transition-all duration-300 group">
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="font-serif text-xl text-white group-hover:text-gold transition-colors">
                                        {{ $request->name }} {{ $request->surname }}
                                    </h3>
                                </div>
                                <div class="flex flex-wrap items-center gap-3 mt-2">
                                    <span class="px-3 py-1 bg-gold/10 text-gold border border-gold/20 rounded text-[0.55rem] tracking-widest uppercase font-medium">
                                        {{ $request->event_type }}
                                    </span>
                                    <span class="text-xs text-ash/50">{{ $request->event_date->format('M d, Y') ?? 'TBD' }}</span>
                                </div>
                                <p class="text-sm text-ash/60 mt-3">{{ $request->event_location }}</p>
                                <div class="flex flex-wrap gap-4 mt-3">
                                    <a href="mailto:{{ $request->email }}" class="text-xs text-gold hover:text-gold-lt transition-colors" onclick="event.stopPropagation()">
                                        {{ $request->email }}
                                    </a>
                                    <a href="tel:{{ $request->phone }}" class="text-xs text-gold hover:text-gold-lt transition-colors" onclick="event.stopPropagation()">
                                        {{ $request->phone }}
                                    </a>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-[0.55rem] tracking-[0.2em] uppercase text-ash/40 mb-1">Submitted</p>
                                <p class="text-xs text-ash/60">{{ $request->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        @if ($bookingRequests->hasPages())
            <div class="pagination-container reveal" style="animation-delay: 100ms">
                {{ $bookingRequests->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
