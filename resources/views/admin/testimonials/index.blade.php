@extends('layouts.app')

@section('header', 'Client Testimonials')

@section('content')
<div class="space-y-12 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-end gap-6 reveal">
        <div>
            <div class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
                <span>Social Proof</span>
                <div class="h-px w-16 bg-gold/30"></div>
            </div>
            <h1 class="font-serif text-4xl text-charcoal dark:text-white leading-tight">Client <em>Voices</em></h1>
            <p class="text-xs tracking-[0.3em] uppercase text-ash/60 dark:text-ash/60 mt-2 italic">Shape the narrative future clients will read</p>
        </div>
        <a href="{{ route('testimonials.create') }}" class="group relative px-8 py-4 bg-gold text-black text-[0.65rem] tracking-[0.3em] uppercase font-bold hover:bg-white transition-all duration-500 overflow-hidden">
            <span class="relative z-10">+ New Testimonial</span>
            <div class="absolute inset-0 bg-white translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-500"></div>
        </a>
    </div>

    <!-- Testimonials Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($testimonials as $testimonial)
            <div class="glass group relative p-8 rounded-xl border border-charcoal/10 dark:border-white/10 transition-all duration-500 hover:border-gold/30 hover:shadow-2xl hover:shadow-gold/5 flex flex-col h-full shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                <div class="flex justify-between items-start mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-graphite border border-iron flex items-center justify-center text-sm text-silver">
                            {{ $testimonial->client_initials ?? substr($testimonial->client_name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-serif text-base text-charcoal dark:text-white leading-tight">{{ $testimonial->client_name }}</p>
                            @if($testimonial->event_label)
                                <p class="text-[0.65rem] tracking-[0.2em] uppercase text-gold mt-1">{{ $testimonial->event_label }}</p>
                            @endif
                        </div>
                    </div>
                    <span class="text-[0.6rem] tracking-[0.4em] uppercase text-gold/40 border border-gold/10 px-2 py-1">Position {{ $testimonial->order }}</span>
                </div>

                <div class="mb-3 text-[0.7rem] tracking-[0.25em] uppercase text-gold">
                    {{ str_repeat('★', $testimonial->rating) }}{{ str_repeat('☆', 5 - $testimonial->rating) }}
                </div>

                <p class="text-sm text-charcoal/80 dark:text-ash italic leading-relaxed line-clamp-5 mb-6">
                    “{{ $testimonial->quote }}”
                </p>

                @if($testimonial->is_featured)
                    <div class="mt-auto mb-4 inline-flex items-center gap-2 text-[0.6rem] tracking-[0.3em] uppercase text-gold">
                        <span class="w-2 h-2 rounded-full bg-gold animate-pulse"></span>
                        <span>Featured</span>
                    </div>
                @endif

                <div class="mt-auto pt-4 border-t border-charcoal/10 dark:border-white/10 flex gap-4">
                    <a href="{{ route('testimonials.edit', $testimonial) }}" class="flex-1 text-center py-2.5 border border-charcoal/10 dark:border-white/10 text-[0.6rem] tracking-[0.3em] uppercase hover:border-gold hover:text-gold transition-all text-charcoal/70 dark:text-silver">
                        Refine
                    </a>
                    <button type="button" onclick="document.getElementById('delete-testimonial-{{ $testimonial->id }}').classList.remove('hidden')" class="flex-1 text-center py-2.5 border border-red-500/20 text-[0.6rem] tracking-[0.3em] uppercase text-red-500/60 hover:bg-red-500/5 hover:text-red-500 transition-all">
                        Remove
                    </button>
                    <x-confirm-modal
                        id="delete-testimonial-{{ $testimonial->id }}"
                        title="Remove Testimonial"
                        message="Are you certain? This action cannot be undone."
                        confirm-label="Remove"
                        :route="route('testimonials.destroy', $testimonial)"
                        method="DELETE"
                        variant="danger"
                    />
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 text-center glass rounded-xl border-dashed border-white/10">
                <p class="font-serif text-xl italic text-silver/40">No clients have spoken on record yet.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

