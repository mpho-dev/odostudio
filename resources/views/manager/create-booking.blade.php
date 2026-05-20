@extends('layouts.app')

@section('title', 'Greenlight Shoot — Odo Studio')

@section('header', 'Studio Command')

@section('content')
<div class="max-w-3xl mx-auto space-y-12 animate-hero-in">
    <!-- Header -->
    <div class="reveal">
        <h1 class="font-serif text-3xl text-charcoal dark:text-white leading-tight">Greenlight <em>Shoot</em></h1>
        <p class="text-xs tracking-[0.3em] uppercase text-ash/60 dark:text-ash/60 mt-2 italic">Convert enquiry into confirmed production</p>
    </div>

    <!-- Enquiry Context -->
    <div class="glass p-10 rounded-xl border border-gold/10 relative overflow-hidden reveal shadow-2xl group">
        <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
        <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
        <div class="absolute top-0 right-0 w-32 h-32 bg-gold/5 blur-3xl -mr-10 -mt-10 rounded-full"></div>
        <div class="flex items-center gap-4 text-[0.6rem] tracking-[0.4em] uppercase text-gold font-bold mb-6">
            <div class="h-px w-8 bg-gold/40"></div>
            <span>Lead Details</span>
        </div>
        <div class="flex flex-col md:flex-row gap-8 items-start md:items-center justify-between">
            <p class="font-serif text-2xl text-charcoal dark:text-white italic leading-tight">
                {{ $bookingRequest->name }} {{ $bookingRequest->surname }}
            </p>
            <div class="flex flex-col items-end">
                <span class="text-[0.6rem] tracking-widest uppercase text-ash/60 dark:text-silver font-medium">{{ $bookingRequest->email }}</span>
                <span class="text-[0.55rem] tracking-widest uppercase text-gold/60 mt-1 italic">Lead Ref: #{{ 5000 + $bookingRequest->id }}</span>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-6 bg-red-500/10 border border-red-500/20 rounded-xl reveal">
            <h4 class="text-[0.6rem] tracking-[0.4rem] uppercase text-red-500 font-bold mb-3">Validation Breach</h4>
            <ul class="text-xs text-red-400 space-y-1 italic">
                @foreach ($errors->all() as $error)
                    <li>— {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="glass p-10 md:p-12 border border-charcoal/10 dark:border-white/10 rounded-xl relative overflow-hidden reveal shadow-2xl group">
        <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
        <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
        <!-- Decorative indicator -->
        <div class="absolute top-0 right-0 w-24 h-px bg-gradient-to-l from-gold/40 to-transparent"></div>

        <form method="POST" action="{{ route('bookings.store', $bookingRequest) }}" class="space-y-8">
            @csrf

            <!-- Crew Assignment -->
            <div class="space-y-3">
                <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Assign Crew</label>
                <p class="text-[0.55rem] tracking-widest uppercase text-ash/60 dark:text-ash/40 italic">Select photographers and/or videographers for this production.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-6">
                    <!-- Photographers -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-2 h-2 bg-gold rounded-full"></div>
                            <span class="text-[0.65rem] tracking-[0.3em] uppercase font-bold text-charcoal dark:text-white">Photographers</span>
                        </div>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @foreach ($photographers as $photographer)
                                <label class="flex items-center gap-3 p-3 bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 rounded cursor-pointer hover:border-gold/30 transition-all">
                                    <input type="checkbox" name="crew_id[photographers][]" 
                                           value="{{ $photographer->id }}"
                                           {{ in_array($photographer->id, old('crew_id.photographers', [])) ? 'checked' : '' }}
                                           class="w-4 h-4 text-gold accent-gold">
                                    <div>
                                        <p class="text-sm font-medium text-charcoal dark:text-white">{{ $photographer->name }}</p>
                                        <p class="text-[0.55rem] text-ash/60 dark:text-ash/50">
                                            {{ $photographer->crew_specialty === 'both' ? 'Photographer & Videographer' : 'Photographer' }}
                                        </p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Videographers -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-2 h-2 bg-gold rounded-full"></div>
                            <span class="text-[0.65rem] tracking-[0.3em] uppercase font-bold text-charcoal dark:text-white">Videographers</span>
                        </div>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @foreach ($videographers as $videographer)
                                <label class="flex items-center gap-3 p-3 bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 rounded cursor-pointer hover:border-gold/30 transition-all">
                                    <input type="checkbox" name="crew_id[videographers][]" 
                                           value="{{ $videographer->id }}"
                                           {{ in_array($videographer->id, old('crew_id.videographers', [])) ? 'checked' : '' }}
                                           class="w-4 h-4 text-gold accent-gold">
                                    <div>
                                        <p class="text-sm font-medium text-charcoal dark:text-white">{{ $videographer->name }}</p>
                                        <p class="text-[0.55rem] text-ash/60 dark:text-ash/50">
                                            {{ $videographer->crew_specialty === 'both' ? 'Photographer & Videographer' : 'Videographer' }}
                                        </p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                @error('crew')
                    <p class="text-[0.55rem] text-red-400 italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                <!-- Event Date -->
                <div class="space-y-3">
                    <label for="event_date" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Shoot Date & Time</label>
                    <input id="event_date" type="datetime-local" name="event_date" 
                           value="{{ old('event_date', $bookingRequest->event_date?->format('Y-m-d\TH:i')) }}" required 
                           class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all" />
                </div>

                <!-- Location -->
                <div class="space-y-3">
                    <label for="location" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Location / Set</label>
                    <input id="location" type="text" name="location" 
                           value="{{ old('location', $bookingRequest->event_location) }}" required 
                           class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all placeholder:italic"
                           placeholder="Studio A / On Location" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                <!-- Investment Tier -->
                <div class="space-y-3">
                    <label for="investment_tier_id" class="block text-[0.6rem] tracking-[0.4em] uppercase text-ash/60 dark:text-silver font-bold">Investment Collection</label>
                    <select id="investment_tier_id" name="investment_tier_id" required 
                            class="w-full bg-charcoal/5 dark:bg-black/40 border border-charcoal/10 dark:border-white/10 p-4 text-sm text-charcoal dark:text-white focus:border-gold outline-none transition-all appearance-none cursor-pointer">
                        <option value="">-- Select Collection --</option>
                        @foreach (\App\Models\InvestmentTier::orderBy('order')->get() as $tier)
                            <option value="{{ $tier->id }}" data-price="{{ $tier->price }}">
                                {{ $tier->name }} (R{{ number_format($tier->price, 2) }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[0.55rem] tracking-widest uppercase text-ash/60 dark:text-ash/40 italic">Determines deliverables and crew rate.</p>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-8 border-t border-charcoal/10 dark:border-white/10 flex justify-end">
                <button type="submit" class="bg-gold text-charcoal px-10 py-4 text-[0.65rem] tracking-[0.3em] uppercase font-bold hover:bg-white transition-all shadow-xl shadow-gold/20">
                    Create Invoice & Book
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
