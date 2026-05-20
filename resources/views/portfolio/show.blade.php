@extends('layouts.guest')

@section('title', $project->title . ' — Odo Studio')

@section('description', Str::limit(strip_tags($project->description), 160))
@section('keywords', $project->category . ', ' . $project->client . ', odo studio case study, cinematic production')
@if($project->hero)
    @section('og_image', media_url($project->hero->file_path))
@endif

@section('content')
<main class="min-h-screen bg-black">
    <!-- Project Hero -->
    <section class="h-[90vh] relative overflow-hidden flex items-end">
        <div class="absolute inset-0 z-0">
            @if($project->hero)
                @php
                    $heroExists = Storage::disk('public')->exists($project->hero->file_path);
                @endphp
                
                @if($heroExists && $project->hero->media_type === 'video')
                    <video src="{{ media_url($project->hero->file_path) }}" class="w-full h-full object-cover" autoplay muted loop playsinline></video>
                @elseif($heroExists && $project->hero->media_type !== 'video')
                    <img src="{{ media_url($project->hero->file_path) }}" class="w-full h-full object-cover" alt="{{ $project->title }}">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-charcoal/60 via-black/80 to-charcoal grid place-items-center">
                        <div class="text-center">
                            <svg class="w-24 h-24 mx-auto text-gold/30 mb-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-silver/40 text-sm tracking-[0.15em] uppercase font-light">Project Visual Coming Soon</p>
                        </div>
                    </div>
                @endif
                <!-- Cinematic darkening -->
                @if($heroExists)
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"></div>
                @endif
            @else
                <div class="w-full h-full bg-gradient-to-br from-charcoal/60 via-black/80 to-charcoal grid place-items-center">
                    <div class="text-center">
                        <svg class="w-24 h-24 mx-auto text-gold/30 mb-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-silver/40 text-sm tracking-[0.15em] uppercase font-light">Project Visual Coming Soon</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="relative z-10 px-6 md:px-12 pb-20 max-w-7xl mx-auto w-full">
            <div class="reveal">
                <div class="section-label group mb-6">
                    <span>{{ $project->category ?? 'Case Study' }}</span>
                </div>
                <h1 class="hero-name text-left text-white mb-4">
                    {{ $project->title }}
                </h1>
                <div class="flex flex-wrap gap-10 mt-12 pt-8 border-t border-white/10">
                    @if($project->client)
                    <div>
                        <p class="text-[0.55rem] tracking-[0.3em] uppercase text-silver mb-2 font-bold">Client</p>
                        <p class="text-white font-serif italic text-lg">{{ $project->client }}</p>
                    </div>
                    @endif
                    @if($project->location)
                    <div>
                        <p class="text-[0.55rem] tracking-[0.3em] uppercase text-silver mb-2 font-bold">Location</p>
                        <p class="text-white font-serif italic text-lg">{{ $project->location }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-[0.55rem] tracking-[0.3em] uppercase text-silver mb-2 font-bold">Release</p>
                        <p class="text-white font-serif italic text-lg">{{ $project->created_at->format('Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Project Narrative -->
    <section class="py-32 px-6 md:px-12 max-w-5xl mx-auto">
        <div class="reveal max-w-3xl">
            <h2 class="section-label mb-12">The Narrative</h2>
            <div class="about-body text-white/90 italic text-[1.4rem] md:text-[1.8rem] leading-[1.6] space-y-8">
                {!! nl2br(e($project->description)) !!}
            </div>
        </div>
    </section>

    <!-- Project Gallery -->
    @if($project->media->count() > 0)
    <section class="py-24 px-6 md:px-12 max-w-[1600px] mx-auto" x-data="{ lightboxOpen: false, lightboxSrc: '', lightboxTitle: '', lightboxType: '' }" @keydown.escape.window="lightboxOpen = false">
        <div class="reveal mb-20 text-center">
            <p class="section-label mb-4 text-center justify-center">Gallery</p>
            <h3 class="section-title italic">Captured Perspectives</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 auto-rows-auto">
            @foreach($project->media as $index => $item)
                @php
                    // Alternate sizes for artistic feel
                    $isTall = $index % 3 == 0;
                    $mediaExists = Storage::disk('public')->exists($item->file_path);
                @endphp
                <div class="reveal group relative overflow-hidden bg-charcoal {{ $isTall ? 'md:row-span-2 aspect-[3/4]' : 'aspect-square md:aspect-video' }} {{ $mediaExists ? 'cursor-pointer' : '' }}"
                     @if($mediaExists) 
                        data-src="{{ media_url($item->file_path) }}"
                        data-title="{{ $item->title }}"
                        data-type="{{ $item->media_type }}"
                        @click="lightboxOpen = true; lightboxSrc = $el.dataset.src; lightboxTitle = $el.dataset.title; lightboxType = $el.dataset.type"
                     @endif>
                    @if($mediaExists && $item->media_type === 'video')
                        <video src="{{ media_url($item->file_path) }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700 pointer-events-none" muted loop playsinline onmouseover="this.play()" onmouseout="this.pause()"></video>
                    @elseif($mediaExists && $item->media_type !== 'video')
                        <img src="{{ media_url($item->file_path) }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-1000 group-hover:scale-105 pointer-events-none" alt="{{ $item->title }}">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-charcoal/80 via-charcoal to-black grid place-items-center">
                            <div class="text-center">
                                <svg class="w-10 h-10 mx-auto text-gold/30 mb-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-silver/40 text-[0.6rem] tracking-[0.15em] uppercase font-light">Coming Soon</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($item->title && $mediaExists)
                    <div class="absolute inset-x-0 bottom-0 p-6 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                        <p class="text-xs text-white font-serif italic">{{ $item->title }}</p>
                    </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Alpine Lightbox Overlay -->
        <template x-teleport="body">
            <div x-show="lightboxOpen" style="display: none;" 
                 class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 backdrop-blur-md p-4 md:p-10"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                 
                <!-- Close Button -->
                <button @click="lightboxOpen = false" class="absolute top-6 right-6 md:top-10 md:right-10 text-white/40 hover:text-white transition-all duration-500 z-10 cursor-pointer group flex flex-col items-center gap-2">
                    <svg class="w-6 h-6 md:w-7 md:h-7 transform group-hover:rotate-90 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span class="text-[0.45rem] tracking-[0.3em] uppercase opacity-0 group-hover:opacity-100 transition-opacity duration-500 font-light">Close</span>
                </button>

                <!-- Media Container -->
                <div class="relative w-full h-full flex flex-col items-center justify-center" @click.self="lightboxOpen = false">
                    <template x-if="lightboxType === 'video'">
                        <video :src="lightboxSrc" class="max-w-full max-h-[85vh] object-contain shadow-2xl" controls autoplay playsinline></video>
                    </template>
                    <template x-if="lightboxType !== 'video'">
                        <img :src="lightboxSrc" :alt="lightboxTitle" class="max-w-full max-h-[85vh] object-contain shadow-2xl"
                             x-transition:enter="transition ease-out duration-500 delay-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100">
                    </template>
                    <p x-show="lightboxTitle" x-text="lightboxTitle" class="text-silver/60 tracking-[0.2em] uppercase text-[0.6rem] mt-6 font-light text-center"></p>
                </div>
            </div>
        </template>
    </section>
    @endif

    <!-- CTA / Navigation -->
    <section class="py-16 px-6 border-t border-white/5">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
            <a href="{{ route('portfolio') }}" class="group flex items-center gap-4 reveal">
                <span class="w-10 h-10 rounded-full border border-white/20 grid place-items-center group-hover:border-gold group-hover:bg-gold transition-all">
                    <span class="text-white group-hover:text-black text-sm">←</span>
                </span>
                <span class="text-[0.55rem] tracking-[0.25em] uppercase text-silver group-hover:text-gold transition-colors">Back to Index</span>
            </a>

            <div class="text-center md:text-right reveal">
                <p class="text-[0.55rem] tracking-[0.25em] uppercase text-gold/60 mb-2">Ready to Start?</p>
                <a href="{{ route('contact.form') }}" class="text-base font-serif italic text-white hover:text-gold transition-colors">Enquire Project →</a>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
    // Specific scripts for project detail if needed
</script>
@endpush
