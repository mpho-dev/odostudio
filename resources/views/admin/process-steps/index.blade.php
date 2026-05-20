@extends('layouts.app')

@section('header', 'Manage Process Steps')

@section('content')
<div class="space-y-12 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-end gap-6 reveal">
        <div>
            <div class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
                <span>Workflow</span>
                <div class="h-px w-16 bg-gold/30"></div>
            </div>
            <h1 class="font-serif text-4xl text-charcoal dark:text-white leading-tight">Process Steps <em>Structure</em></h1>
            <p class="text-xs tracking-[0.3em] uppercase text-ash/60 dark:text-ash/60 mt-2 italic">Define how you collaborate with clients</p>
        </div>
        <a href="{{ route('process-steps.create') }}" class="group relative px-8 py-4 bg-gold text-black text-[0.65rem] tracking-[0.3em] uppercase font-bold hover:bg-white transition-all duration-500 overflow-hidden">
            <span class="relative z-10">+ New Step</span>
            <div class="absolute inset-0 bg-white translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-500"></div>
        </a>
    </div>

    <!-- Collection Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($processSteps as $step)
            <div class="glass group relative p-8 rounded-xl border border-charcoal/10 dark:border-white/10 transition-all duration-500 hover:border-gold/30 hover:shadow-2xl hover:shadow-gold/5 flex flex-col h-full shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                <!-- Step Number -->
                <div class="flex justify-between items-start mb-8">
                    <div class="w-16 h-16 border border-gold/30 flex items-center justify-center text-3xl group-hover:border-gold transition-colors">
                        <span class="font-serif text-gold text-2xl font-light">{{ str_pad($step->step_number, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <span class="text-[0.6rem] tracking-[0.4em] uppercase text-gold/40 border border-gold/10 px-2 py-1">Order {{ $step->display_order }}</span>
                </div>

                <!-- Content -->
                <h3 class="font-serif text-xl text-charcoal dark:text-white mb-3 group-hover:text-gold transition-colors">{{ $step->title }}</h3>
                <p class="text-xs text-charcoal/70 dark:text-silver leading-relaxed line-clamp-3 mb-8 italic opacity-70 group-hover:opacity-100 transition-opacity">
                    {{ $step->description }}
                </p>

                <!-- Actions -->
                <div class="flex gap-4 mt-auto pt-6 border-t border-charcoal/10 dark:border-white/10">
                    <a href="{{ route('process-steps.edit', $step) }}" class="flex-1 text-center py-2.5 border border-charcoal/10 dark:border-white/10 text-[0.6rem] tracking-[0.3em] uppercase hover:border-gold hover:text-gold transition-all text-charcoal/70 dark:text-silver">Edit</a>
                    <button type="button" onclick="document.getElementById('delete-step-{{ $step->id }}').classList.remove('hidden')" class="flex-1 text-center py-2.5 border border-red-500/20 text-[0.6rem] tracking-[0.3em] uppercase text-red-500/60 hover:bg-red-500/5 hover:text-red-500 transition-all">Remove</button>
                    <x-confirm-modal
                        id="delete-step-{{ $step->id }}"
                        title="Remove Process Step"
                        message="Are you certain? This action cannot be undone."
                        confirm-label="Remove"
                        :route="route('process-steps.destroy', $step)"
                        method="DELETE"
                        variant="danger"
                    />
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 text-center glass rounded-xl border-dashed border-white/10">
                <p class="font-serif text-xl italic text-silver/40">No process steps defined yet.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
