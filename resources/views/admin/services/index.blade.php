@extends('layouts.app')

@section('header', 'Manage Services')

@section('content')
<div class="space-y-12 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-end gap-6 reveal">
        <div>
            <div class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
                <span>Infrastructure</span>
                <div class="h-px w-16 bg-gold/30"></div>
            </div>
            <h1 class="font-serif text-4xl text-charcoal dark:text-white leading-tight">Service <em>Offerings</em></h1>
            <p class="text-xs tracking-[0.3em] uppercase text-ash/60 dark:text-ash/60 mt-2 italic">Curate the aesthetic and logistical framework of your productions</p>
        </div>
        <a href="{{ route('services.create') }}" class="group relative px-8 py-4 bg-gold text-black text-[0.65rem] tracking-[0.3em] uppercase font-bold hover:bg-white transition-all duration-500 overflow-hidden">
            <span class="relative z-10">+ New Offering</span>
            <div class="absolute inset-0 bg-white translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-500"></div>
        </a>
    </div>

    <!-- Collection Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($services as $service)
            <div class="glass group relative p-8 rounded-xl border border-charcoal/10 dark:border-white/10 transition-all duration-500 hover:border-gold/30 hover:shadow-2xl hover:shadow-gold/5 flex flex-col h-full shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                <!-- Icon & Order -->
                <div class="flex justify-between items-start mb-8">
                    <div class="w-12 h-12 border border-gold/20 flex items-center justify-center text-2xl group-hover:border-gold/50 transition-colors">
                        <span class="opacity-80 group-hover:opacity-100 transition-opacity">
                            {{ $service->icon ?? '🎬' }}
                        </span>
                    </div>
                    <span class="text-[0.6rem] tracking-[0.4em] uppercase text-gold/40 border border-gold/10 px-2 py-1">Position {{ $service->order }}</span>
                </div>

                <!-- Content -->
                <h3 class="font-serif text-2xl text-charcoal dark:text-white mb-3 group-hover:text-gold transition-colors">{{ $service->title }}</h3>
                <p class="text-xs text-charcoal/70 dark:text-silver leading-relaxed line-clamp-3 mb-8 italic opacity-70 group-hover:opacity-100 transition-opacity">
                    {{ $service->description }}
                </p>

                <div class="mt-auto space-y-6">
                    <div class="flex justify-between items-end border-t border-charcoal/10 dark:border-white/10 pt-6">
                        <div>
                            <p class="text-[0.55rem] tracking-[0.3em] uppercase text-ash/40 mb-1">Base Investment</p>
                            <p class="font-serif text-xl text-charcoal dark:text-white">R {{ number_format($service->starting_price, 0, '.', ' ') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[0.55rem] tracking-[0.3em] uppercase text-ash/40 mb-1">Deliverables</p>
                            <p class="text-[0.6rem] text-gold uppercase font-bold tracking-widest">{{ count($service->features ?? []) }} Aspects</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-4 pt-4">
                        <a href="{{ route('services.edit', $service) }}" class="flex-1 text-center py-2.5 bg-charcoal/5 dark:bg-white/5 border border-charcoal/10 dark:border-white/10 text-[0.6rem] tracking-[0.3em] uppercase hover:border-gold hover:text-gold transition-all text-charcoal/70 dark:text-silver font-bold">Refine Design</a>
                        <button type="button" onclick="document.getElementById('delete-service-{{ $service->id }}').classList.remove('hidden')" class="flex-1 text-center py-2.5 border border-red-500/20 text-[0.6rem] tracking-[0.3em] uppercase text-red-500/60 hover:bg-red-500/5 hover:text-red-500 transition-all font-bold">Purge</button>
                        <x-confirm-modal
                            id="delete-service-{{ $service->id }}"
                            title="Archive Offering"
                            message="Archive this offering permanently? This action cannot be undone."
                            confirm-label="Purge"
                            :route="route('services.destroy', $service)"
                            method="DELETE"
                            variant="danger"
                        />
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 text-center glass rounded-xl border-dashed border-white/10 shadow-2xl relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                <p class="font-serif text-xl italic text-silver/40">The collection is currently void.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
