@extends('layouts.app')

@section('header', 'Portfolio Projects')

@section('content')
<div class="space-y-12 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-end gap-6 reveal">
        <div>
            <div class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
                <span>Showcase</span>
                <div class="h-px w-16 bg-gold/30"></div>
            </div>
            <h1 class="font-serif text-4xl text-charcoal dark:text-white leading-tight">Curated <em>Projects</em></h1>
            <p class="text-xs tracking-[0.3em] uppercase text-ash/60 dark:text-ash/60 mt-2 italic">Manage case studies and media collections</p>
        </div>
        <a href="{{ route('projects.create') }}" class="group relative px-8 py-4 bg-gold text-black text-[0.65rem] tracking-[0.3em] uppercase font-bold hover:bg-white transition-all duration-500 overflow-hidden">
            <span class="relative z-10">+ New Case Study</span>
            <div class="absolute inset-0 bg-white translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-500"></div>
        </a>
    </div>

    <!-- Projects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($projects as $project)
            <div class="glass group relative overflow-hidden rounded-xl border border-charcoal/10 dark:border-white/10 transition-all duration-500 hover:border-gold/30 hover:shadow-2xl hover:shadow-gold/5 flex flex-col h-full reveal">
                <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                <!-- Hero Image Preview -->
                <div class="h-48 overflow-hidden bg-charcoal dark:bg-charcoal">
                    @if($project->hero)
                        @php
                            $fileExists = \Illuminate\Support\Facades\Storage::disk('public')->exists($project->hero->file_path);
                        @endphp
                        @if($fileExists)
                            @if($project->hero->media_type === 'video')
                                <video src="{{ media_url($project->hero->file_path) }}" class="w-full h-full object-cover opacity-60 dark:opacity-60 group-hover:scale-110 transition-transform duration-700" muted></video>
                            @else
                                <img src="{{ media_url($project->hero->file_path) }}" 
                                     class="w-full h-full object-cover opacity-60 dark:opacity-60 group-hover:scale-110 transition-transform duration-700"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';">
                                <div class="w-full h-full hidden bg-gradient-to-br from-charcoal to-graphite grid place-items-center">
                                    <svg class="w-12 h-12 text-ash/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-charcoal to-graphite grid place-items-center">
                                <svg class="w-12 h-12 text-ash/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-charcoal to-graphite grid place-items-center">
                            <svg class="w-12 h-12 text-ash/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Project Info -->
                <div class="p-6 flex-1 flex flex-col">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-[0.55rem] tracking-[0.3em] uppercase text-gold py-1 px-2 border border-gold/30 rounded">
                            {{ $project->category ?? 'General' }}
                        </span>
                        <button type="button" onclick="document.getElementById('delete-project-{{ $project->id }}').classList.remove('hidden')" class="text-red-500/60 hover:text-red-500 transition-colors">Delete</button>
                        <x-confirm-modal
                            id="delete-project-{{ $project->id }}"
                            title="Archive Case Study"
                            message="Archive this case study? This action cannot be undone."
                            confirm-label="Archive"
                            :route="route('projects.destroy', $project)"
                            method="DELETE"
                            variant="danger"
                        />
                    </div>
                    
                    <h3 class="font-serif text-xl text-charcoal dark:text-white mb-3 leading-tight group-hover:text-gold transition-colors">{{ $project->title }}</h3>
                    <p class="text-xs text-charcoal/70 dark:text-silver leading-relaxed line-clamp-3 italic mb-6 opacity-70 group-hover:opacity-100 transition-opacity">{{ $project->description }}</p>
                    
                    <div class="mt-auto pt-4 border-t border-charcoal/10 dark:border-white/10">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <p class="text-[0.55rem] tracking-[0.3em] uppercase text-ash/40">Media Collection</p>
                                <p class="text-sm text-gold font-medium">{{ $project->media->count() }} Items</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[0.55rem] tracking-[0.3em] uppercase text-ash/40">Display Order</p>
                                <p class="text-sm text-charcoal dark:text-silver font-medium">{{ $project->order }}</p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <a href="{{ route('projects.edit', $project) }}" class="flex-1 text-center py-2.5 border border-charcoal/10 dark:border-white/10 text-[0.6rem] tracking-[0.3em] uppercase hover:border-gold hover:text-gold transition-all text-charcoal/70 dark:text-silver font-bold">Refine Project</a>
                        </div>
                    </div>
                </div>

                <!-- Featured Badge -->
                @if($project->is_featured)
                    <div class="absolute top-4 right-4 bg-gold text-black text-[0.5rem] tracking-[0.2em] uppercase font-bold px-2 py-1 shadow-xl">Featured</div>
                @endif
            </div>
        @empty
            <div class="col-span-full py-24 text-center glass rounded-xl border-dashed border-white/10 shadow-2xl relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                <p class="font-serif text-xl italic text-silver/40">The archives are currently empty.</p>
                <a href="{{ route('projects.create') }}" class="mt-4 inline-block text-[0.6rem] tracking-widest uppercase text-gold hover:underline">Begin your first case study</a>
            </div>
        @endforelse
    </div>
</div>
@endsection
