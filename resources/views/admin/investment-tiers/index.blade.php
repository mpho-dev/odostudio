@extends('layouts.app')

@section('header', 'Investment Tiers')

@section('content')
<div class="space-y-12 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-end gap-6 reveal">
        <div>
            <div class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
                <span>Commercial</span>
                <div class="h-px w-16 bg-gold/30"></div>
            </div>
            <h1 class="font-serif text-4xl text-charcoal dark:text-white leading-tight">Investment <em>Structure</em></h1>
            <p class="text-xs tracking-[0.3em] uppercase text-ash/60 dark:text-ash/60 mt-2 italic">Curate the primary collection tiers for clients</p>
        </div>
        <a href="{{ route('investment-tiers.create') }}" class="group relative px-8 py-4 bg-gold text-black text-[0.65rem] tracking-[0.3em] uppercase font-bold hover:bg-white transition-all duration-500 overflow-hidden">
            <span class="relative z-10">+ New Tier</span>
            <div class="absolute inset-0 bg-white translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-500"></div>
        </a>
    </div>

    <!-- Tiers Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tiers as $tier)
            <div class="glass group relative p-8 rounded-xl border border-charcoal/10 dark:border-white/10 transition-all duration-500 hover:border-gold/30 hover:shadow-2xl hover:shadow-gold/5 flex flex-col h-full shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <p class="text-[0.6rem] tracking-[0.3em] uppercase text-gold/70 mb-1">{{ $tier->tier_label }}</p>
                        <h3 class="font-serif text-2xl text-charcoal dark:text-white group-hover:text-gold transition-colors">{{ $tier->name }}</h3>
                    </div>
                    <span class="text-[0.6rem] tracking-[0.4em] uppercase text-gold/40 border border-gold/10 px-2 py-1">Position {{ $tier->order }}</span>
                </div>

                @if($tier->is_featured)
                    <div class="inline-flex items-center gap-2 text-[0.6rem] tracking-[0.3em] uppercase text-gold mb-4">
                        <span class="w-2 h-2 rounded-full bg-gold animate-pulse"></span>
                        <span>{{ $tier->badge_label ?? 'Featured' }}</span>
                    </div>
                @endif

                <div class="mb-6">
                    <p class="font-serif text-3xl text-gold-lt leading-none">
                        R {{ number_format($tier->price, 2) }}
                        @if($tier->price_suffix)
                            <span class="text-sm text-silver font-sans ml-1">{{ $tier->price_suffix }}</span>
                        @endif
                    </p>
                </div>

                @if($tier->features)
                    <ul class="text-[0.75rem] text-charcoal/70 dark:text-ash/70 space-y-2 mb-6">
                        @foreach($tier->features as $feature)
                            <li class="flex items-start gap-2">
                                <span class="text-gold text-sm mt-0.5">✓</span>
                                <span>{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <div class="mt-auto pt-4 border-t border-charcoal/10 dark:border-white/10 flex gap-4">
                    <a href="{{ route('investment-tiers.edit', $tier) }}" class="flex-1 text-center py-2.5 border border-charcoal/10 dark:border-white/10 text-[0.6rem] tracking-[0.3em] uppercase hover:border-gold hover:text-gold transition-all text-charcoal/70 dark:text-silver">
                        Refine Tier
                    </a>
                    <button type="button" onclick="document.getElementById('delete-tier-{{ $tier->id }}').classList.remove('hidden')" class="flex-1 text-center py-2.5 border border-red-500/20 text-[0.6rem] tracking-[0.3em] uppercase text-red-500/60 hover:bg-red-500/5 hover:text-red-500 transition-all">
                        Remove
                    </button>
                    <x-confirm-modal
                        id="delete-tier-{{ $tier->id }}"
                        title="Remove Investment Tier"
                        message="Are you certain? This action cannot be undone."
                        confirm-label="Remove"
                        :route="route('investment-tiers.destroy', $tier)"
                        method="DELETE"
                        variant="danger"
                    />
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 text-center glass rounded-xl border-dashed border-white/10">
                <p class="font-serif text-xl italic text-silver/40">No investment tiers have been composed yet.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

