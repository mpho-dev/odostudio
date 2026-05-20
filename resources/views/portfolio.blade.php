@extends('layouts.guest')

@section('title', 'Portfolio — Odo Studio')

@section('description', 'Explore Odo Studio\'s collection of cinematic photography and videography projects. From high-profile brand films to intimate wedding stories.')
@section('keywords', 'portfolio, cinematic projects, photography gallery, odo studio showcase')

@section('content')
    @php
        $fallbackEmojis = ['✦', '◇', '○', '□', '△', '▽', '◎', '●', '◈', '◉', '❖', '❐'];

        $gradientClasses = [
            'from-amber-950/90 via-amber-900/80 to-neutral-900',
            'from-slate-900 via-slate-800/90 to-slate-900',
            'from-stone-900 via-stone-800/80 to-stone-950',
            'from-zinc-900 via-zinc-800/90 to-zinc-900',
            'from-emerald-950/80 via-emerald-900/70 to-zinc-900',
            'from-orange-950/80 via-amber-900/70 to-stone-900',
        ];
    @endphp

    <div class="subpage-header-padding pb-24 px-6 md:px-12 max-w-7xl mx-auto min-h-screen">
        <!-- Portfolio Header -->
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
            <div class="reveal">
                <div class="section-label group mb-4">
                    <span>Selection</span>
                </div>
                <h1 class="section-title text-left">
                    Our <em>portfolio</em>
                </h1>
            </div>

            <!-- Category Filters -->
            <div class="flex flex-wrap gap-2 reveal">
                <a href="{{ route('portfolio') }}" 
                   class="px-5 py-2 text-[0.65rem] tracking-[0.2em] uppercase border transition-all duration-200 {{ !request('category') ? 'border-gold text-gold active' : 'border-charcoal/20 dark:border-iron text-charcoal/60 dark:text-silver hover:border-gold hover:text-gold' }}">
                    All Works
                </a>
                @if(isset($categories) && $categories->isNotEmpty())
                    @foreach($categories as $cat)
                        <a href="{{ route('portfolio', ['category' => $cat]) }}" 
                           class="px-5 py-2 text-[0.65rem] tracking-[0.2em] uppercase border transition-all duration-200 {{ request('category') === $cat ? 'border-gold text-gold active' : 'border-charcoal/20 dark:border-iron text-charcoal/60 dark:text-silver hover:border-gold hover:text-gold' }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                @endif
            </div>
        </div>

        @if ($projects->isEmpty())
            <div class="py-20 text-center border border-dashed border-charcoal/20 dark:border-iron rounded-lg">
                <p class="text-charcoal/40 dark:text-silver italic font-serif text-xl">No works discovered in this collection yet.</p>
            </div>
        @else
            <!-- Artistic Grid -->
            <div class="grid grid-cols-12 gap-4 md:gap-6 auto-rows-auto">
                @foreach ($projects as $index => $project)
                    @php
                        $gridPos = $index % 6;
                        $spanClass = '';
                        switch ($gridPos) {
                            case 0: $spanClass = 'col-span-12 md:col-span-7 row-span-1 md:row-span-2'; break;
                            case 1: $spanClass = 'col-span-12 md:col-span-5'; break;
                            case 2: $spanClass = 'col-span-12 md:col-span-5'; break;
                            case 3: $spanClass = 'col-span-12 md:col-span-4'; break;
                            case 4: $spanClass = 'col-span-12 md:col-span-4'; break;
                            case 5: $spanClass = 'col-span-12 md:col-span-4'; break;
                        }
                        
                        $mediaItem = $project->hero;
                        $category = $project->category ?? 'Perspective';
                        $fallbackIcon = $fallbackEmojis[array_rand($fallbackEmojis)];
                        $gradientClass = $gradientClasses[$index % count($gradientClasses)];
                    @endphp

                    <a href="{{ route('portfolio.project', $project->slug) }}" class="group relative overflow-hidden bg-charcoal {{ $spanClass }} reveal">
                        <div class="aspect-[4/3] md:aspect-auto h-full w-full overflow-hidden">
                            @if ($mediaItem)
                                @php
                                    $fileExists = Storage::disk('public')->exists($mediaItem->file_path);
                                @endphp
                                
                                @if ($fileExists && ($mediaItem->media_type === 'image' || $mediaItem->media_type === 'gif'))
                                    <img src="{{ media_url($mediaItem->file_path) }}" 
                                         alt="{{ $mediaItem->title ?? $project->title }}" 
                                         class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110 grayscale group-hover:grayscale-0">
                                @elseif ($fileExists && $mediaItem->media_type === 'video')
                                    <div class="relative w-full h-full">
                                        <video class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700" 
                                               muted loop playsinline onmouseover="this.play()" onmouseout="this.pause()">
                                            <source src="{{ media_url($mediaItem->file_path) }}" type="video/mp4">
                                        </video>
                                        <div class="absolute top-4 right-4 text-white/50 text-[0.5rem] tracking-[0.2em] uppercase p-1 bg-black/40 backdrop-blur-md">Motion</div>
                                    </div>
                                @else
                                    <div class="w-full h-full bg-gradient-to-br {{ $gradientClass }} grid place-items-center p-8">
                                        <span class="text-5xl md:text-6xl opacity-20 select-none">{{ $fallbackIcon }}</span>
                                    </div>
                                @endif
                                
                                <div class="absolute top-4 left-4 bg-gold text-black text-[0.5rem] tracking-[0.3em] uppercase font-bold px-2 py-1 shadow-lg z-10">Case Study</div>
                            @else
                                <div class="w-full h-full bg-gradient-to-br {{ $gradientClass }} grid place-items-center p-8">
                                    <span class="text-5xl md:text-6xl opacity-20 select-none">{{ $fallbackIcon }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/85 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-350 flex items-end p-5">
                            <div class="translate-y-4 group-hover:translate-y-0 transition-transform duration-350">
                                <p class="text-[0.6rem] tracking-[0.25em] uppercase text-gold mb-0.5">{{ $category }}</p>
                                <h3 class="font-serif text-base text-white leading-tight mb-0.5">
                                    {{ $project->title ?? 'Untitled Concept' }}
                                </h3>
                                <p class="text-[0.6rem] text-silver/70 italic font-serif mt-1">Explore →</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    // Portfolio specific interactivity can be added here
</script>
@endpush
