@extends('layouts.app')

@section('title', 'Studio Overview — Odo Studio')

@section('header', 'Production Overview')

@section('content')
<div class="space-y-12">
    <!-- Header -->
    <div class="reveal">
        <h2 class="font-serif text-3xl text-charcoal dark:text-white leading-tight">Studio <em>Intelligence</em></h2>
        <p class="text-xs tracking-[0.3em] uppercase text-ash/60 dark:text-ash/60 mt-2 italic">Executive oversight of ongoing high-end projects</p>
    </div>

    <!-- Analytics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Pending Requests -->
        <div class="glass p-8 rounded-xl border border-charcoal/10 dark:border-white/10 relative group overflow-hidden transition-all duration-500 animate-hero-in shadow-2xl">
            <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <div class="absolute top-0 left-0 w-1 h-full bg-gold/40 group-hover:bg-gold transition-colors"></div>
            <div class="flex justify-between items-start mb-6">
                <div>
                    <p class="text-[0.55rem] tracking-[0.3em] uppercase text-ash/60 dark:text-ash/40 mb-1 font-bold">New Leads</p>
                    <p class="text-4xl font-serif text-charcoal dark:text-white group-hover:text-gold transition-colors">{{ $pendingRequests }}</p>
                </div>
                <span class="text-gold/40 group-hover:text-gold transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </span>
            </div>
            <a href="{{ route('requests.index') }}" class="text-[0.6rem] tracking-[0.4em] uppercase text-gold hover:text-charcoal dark:hover:text-white transition-colors flex items-center gap-2">
                View Leads <span>→</span>
            </a>
        </div>

        <!-- Active Bookings -->
        <div class="glass p-8 rounded-xl border border-charcoal/10 dark:border-white/10 relative group overflow-hidden transition-all duration-500 animate-hero-in shadow-2xl" style="animation-delay: 100ms;">
            <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <div class="absolute top-0 left-0 w-1 h-full bg-gold/40 group-hover:bg-gold transition-colors"></div>
            <div class="flex justify-between items-start mb-6">
                <div>
                    <p class="text-[0.55rem] tracking-[0.3em] uppercase text-ash/60 dark:text-ash/40 mb-1 font-bold">Live Sets</p>
                    <p class="text-4xl font-serif text-charcoal dark:text-white group-hover:text-gold transition-colors">{{ $activeBookings }}</p>
                </div>
                <span class="text-gold/40 group-hover:text-gold transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                </span>
            </div>
            <a href="{{ route('bookings.index') }}" class="text-[0.6rem] tracking-[0.4em] uppercase text-gold hover:text-charcoal dark:hover:text-white transition-colors flex items-center gap-2">
                Call Sheets <span>→</span>
            </a>
        </div>

        <!-- Draft Invoices -->
        <div class="glass p-8 rounded-xl border border-charcoal/10 dark:border-white/10 relative group overflow-hidden transition-all duration-500 animate-hero-in shadow-2xl" style="animation-delay: 200ms;">
            <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <div class="absolute top-0 left-0 w-1 h-full bg-gold/40 group-hover:bg-gold transition-colors"></div>
            <div class="flex justify-between items-start mb-6">
                <div>
                    <p class="text-[0.55rem] tracking-[0.3em] uppercase text-ash/60 dark:text-ash/40 mb-1 font-bold">Outstanding Retainers</p>
                    <p class="text-4xl font-serif text-charcoal dark:text-white group-hover:text-gold transition-colors">{{ $draftInvoices }}</p>
                </div>
                <span class="text-gold/40 group-hover:text-gold transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </span>
            </div>
            <a href="{{ route('invoices.index') }}" class="text-[0.6rem] tracking-[0.4em] uppercase text-gold hover:text-charcoal dark:hover:text-white transition-colors flex items-center gap-2">
                Billing Status <span>→</span>
            </a>
        </div>

        <!-- Total Revenue -->
        <div class="glass p-8 rounded-xl border border-charcoal/10 dark:border-white/10 relative group overflow-hidden transition-all duration-500 animate-hero-in shadow-2xl" style="animation-delay: 300ms;">
            <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <div class="absolute top-0 left-0 w-1 h-full bg-gold/40 group-hover:bg-gold transition-colors"></div>
            <div class="flex justify-between items-start mb-6">
                <div>
                    <p class="text-[0.55rem] tracking-[0.3em] uppercase text-ash/60 dark:text-ash/40 mb-1 font-bold">Gross Revenue</p>
                    <p class="text-3xl font-serif text-charcoal dark:text-white group-hover:text-gold transition-colors">R{{ number_format($totalRevenue, 2) }}</p>
                </div>
                <span class="text-gold/40 group-hover:text-gold transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
            <p class="text-[0.55rem] tracking-[0.2em] uppercase text-ash/60 dark:text-ash/40 italic">Total value of settled productions</p>
        </div>
    </div>

    <!-- Quick Access Section -->
    <div class="pt-12 border-t border-charcoal/10 dark:border-white/10 space-y-8 reveal">
        <h2 class="text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold">Quick Actions</h2>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('requests.index') }}" class="bg-charcoal/5 dark:bg-white/5 border border-charcoal/10 dark:border-white/10 hover:border-gold/30 hover:bg-gold/10 text-charcoal/70 dark:text-silver hover:text-gold px-8 py-4 text-[0.65rem] tracking-[0.3em] uppercase font-bold transition-all">Review Enquiries</a>
            <a href="{{ route('bookings.index') }}" class="bg-charcoal/5 dark:bg-white/5 border border-charcoal/10 dark:border-white/10 hover:border-gold/30 hover:bg-gold/10 text-charcoal/70 dark:text-silver hover:text-gold px-8 py-4 text-[0.65rem] tracking-[0.3em] uppercase font-bold transition-all">Manage Productions</a>
            <a href="{{ route('invoices.index') }}" class="bg-charcoal/5 dark:bg-white/5 border border-charcoal/10 dark:border-white/10 hover:border-gold/30 hover:bg-gold/10 text-charcoal/70 dark:text-silver hover:text-gold px-8 py-4 text-[0.65rem] tracking-[0.3em] uppercase font-bold transition-all">Financial Audit</a>
        </div>
    </div>
</div>
@endsection
