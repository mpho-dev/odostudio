@extends('layouts.app')

@section('title', 'Communications — Odo Studio')

@section('header', 'System Preferences')

@section('content')
<div class="max-w-2xl mx-auto space-y-12 animate-hero-in pb-24 md:pb-8">
    <!-- Header Section -->
    <div class="reveal">
        <h1 class="font-serif text-3xl text-charcoal dark:text-white leading-tight">Notification <em>Protocols</em></h1>
        <p class="text-xs tracking-[0.3em] uppercase text-ash/60 dark:text-ash/60 mt-2 italic">Configure your automated alert settings</p>
    </div>

    @if ($errors->any())
        <div class="border border-red-500/30 bg-red-500/10 p-6 reveal">
            <p class="text-[0.65rem] tracking-[0.2em] uppercase font-bold text-red-500">{{ $errors->first() }}</p>
        </div>
    @endif

    <div class="glass p-10 md:p-12 relative overflow-hidden group border border-charcoal/10 dark:border-white/10 rounded-xl reveal shadow-2xl" style="animation-delay: 100ms;">
        <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
        <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
        
        <form action="{{ route('photographer.preferences.update') }}" method="POST" class="space-y-10">
            @csrf
            @method('PATCH')

            <div class="space-y-4">
                <label class="flex items-start cursor-pointer group/toggle">
                    <div class="relative flex items-center h-5 mt-1">
                        <input 
                            type="checkbox" 
                            name="notify_new_requests" 
                            value="1"
                            {{ $preference->notify_new_requests ? 'checked' : '' }}
                            class="peer sr-only"
                        >
                        <!-- Custom Toggle UI -->
                        <div class="w-10 h-5 bg-charcoal/20 dark:bg-white/10 rounded-full border border-charcoal/20 dark:border-white/20 peer-checked:bg-gold/20 peer-checked:border-gold/50 transition-all duration-300"></div>
                        <div class="absolute left-1 w-3 h-3 bg-ash/60 dark:bg-ash/40 rounded-full peer-checked:bg-gold peer-checked:translate-x-5 transition-all duration-300 shadow-sm"></div>
                    </div>
                    <div class="ml-4 flex-1">
                        <span class="block font-serif text-lg text-charcoal dark:text-white group-hover/toggle:text-gold transition-colors">
                            Inbound Enquiry Alerts
                        </span>
                        <span class="block text-[0.6rem] tracking-[0.2em] uppercase text-ash/60 dark:text-ash/40 mt-2 italic leading-relaxed">
                            Receive immediate email dispatches when new production enquiries are logged in the system.
                        </span>
                    </div>
                </label>
            </div>

            <div class="flex gap-4 pt-6 border-t border-charcoal/10 dark:border-white/10">
                <button type="submit" class="flex-1 bg-gold hover:bg-gold/90 text-black px-6 py-4 text-[0.65rem] tracking-[0.3em] uppercase font-bold transition-all text-center">
                    Commit Changes
                </button>
                <a href="{{ route('photographer.calendar') }}" class="flex-1 bg-charcoal/5 dark:bg-white/5 border border-charcoal/10 dark:border-white/10 hover:border-gold hover:text-gold text-charcoal/60 dark:text-silver px-6 py-4 text-[0.65rem] tracking-[0.3em] uppercase font-bold transition-all text-center">
                    Dashboard
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
