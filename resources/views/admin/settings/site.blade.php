@extends('layouts.app')

@section('title', 'Site Narrative Configuration — Odo Studio')

@section('header', 'Site Narrative')

@section('content')
<div class="max-w-4xl mx-auto space-y-12 animate-hero-in pb-20">
    <div class="reveal">
        <h1 class="font-serif text-3xl text-white leading-tight">Visual <em>Narrative</em></h1>
        <p class="text-xs tracking-[0.3em] uppercase text-ash/60 mt-2 italic">Configure the cinematic identity of your landing page</p>
    </div>

    <form method="POST" action="{{ route('admin.site-config.update') }}" enctype="multipart/form-data" class="space-y-10">
        @csrf

        <!-- ── HERO SECTION ── -->
        <div class="glass p-8 md:p-10 border border-charcoal/10 dark:border-white/10 rounded-xl relative overflow-hidden group shadow-2xl">
            <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <h2 class="text-[0.65rem] tracking-[0.4em] uppercase text-gold font-bold mb-8 flex items-center gap-3">
                <span class="w-8 h-px bg-gold/30"></span> 01. Hero Section
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-white font-bold">Eyebrow Text</label>
                    <input type="text" name="hero_eyebrow" value="{{ $settings['hero_eyebrow'] ?? '' }}" 
                           class="w-full bg-charcoal/80 border border-white/10 p-4 text-sm text-white focus:border-gold outline-none transition-all placeholder:text-ash/40 italic" />
                </div>
                <div class="space-y-3">
                    <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-white font-bold">Studio Name (HTML Support)</label>
                    <input type="text" name="hero_name" value="{{ $settings['hero_name'] ?? '' }}" 
                           class="w-full bg-charcoal/80 border border-white/10 p-4 text-sm text-white focus:border-gold outline-none transition-all placeholder:text-ash/40 italic" />
                </div>
                    <div class="md:col-span-2 space-y-3">
                    <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-silver font-bold">Hero Title (Cinematic Headline)</label>
                    <div class="bg-white rounded-lg">
                        <div class="quill-editor-container h-48 text-black" data-target="hero_title" data-quill-mode="inline"></div>
                    </div>
                    <input type="hidden" id="hero_title" name="hero_title" value="{{ $settings['hero_title'] ?? '' }}">
                </div>
                <div class="space-y-3">
                    <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-silver font-bold">Scroll Text (Bottom of Hero)</label>
                    <input type="text" name="hero_scroll" value="{{ $settings['hero_scroll'] ?? '' }}" 
                           class="w-full bg-charcoal/80 border border-white/10 p-4 text-sm text-white focus:border-gold outline-none transition-all placeholder:italic" placeholder="e.g. Mpumalanga · Gauteng · Worldwide" />
                    <p class="text-[0.55rem] tracking-widest uppercase text-ash/40 italic">Text displayed at the bottom of the hero section</p>
                </div>
                <div class="md:col-span-2 space-y-3">
                    <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-white font-bold">Hero Background Image</label>
                    <div class="flex items-center gap-6">
                        @php
                            $heroImagePath = $settings['hero_bg_image'] ?? null;
                            $heroImageExists = $heroImagePath && \Illuminate\Support\Facades\Storage::disk('public')->exists(str_replace('/storage/', '', $heroImagePath));
                        @endphp
                        @if($heroImageExists)
                            <div class="w-20 h-20 rounded border border-white/10 overflow-hidden bg-charcoal/80">
                                <img src="{{ $heroImagePath }}" class="w-full h-full object-cover" />
                            </div>
                        @else
                            <div class="w-20 h-20 rounded border border-white/10 bg-gradient-to-br from-charcoal to-graphite flex items-center justify-center">
                                <svg class="w-8 h-8 text-gold/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                        <input type="file" name="hero_bg_image" class="text-xs text-ash file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[0.6rem] file:font-semibold file:bg-charcoal/10 file:text-gold hover:file:bg-white/10 cursor-pointer" />
                    </div>
                    @if($heroImageExists)
                        <label class="flex items-center gap-2 text-xs text-ash/60 cursor-pointer hover:text-red-400 transition-colors">
                            <input type="checkbox" name="remove_hero_bg_image" value="1" class="w-4 h-4 rounded border-white/20 bg-charcoal/50 text-red-500 focus:ring-red-500/50 focus:ring-2">
                            <span>Remove current hero image</span>
                        </label>
                    @endif
                </div>
            </div>
        </div>

        <!-- ── ABOUT & DIRECTOR SECTION ── -->
        <div class="glass p-8 md:p-10 border border-charcoal/10 dark:border-white/10 rounded-xl relative overflow-hidden group shadow-2xl">
            <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <h2 class="text-[0.65rem] tracking-[0.4em] uppercase text-gold font-bold mb-8 flex items-center gap-3">
                <span class="w-8 h-px bg-gold/30"></span> 02. The Narrative
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-white font-bold">Narrative Eyebrow</label>
                    <input type="text" name="about_narrative_eyebrow" value="{{ $settings['about_narrative_eyebrow'] ?? '' }}" 
                           class="w-full bg-charcoal/80 border border-white/10 p-4 text-sm text-white focus:border-gold outline-none transition-all placeholder:italic" />
                </div>
                <div class="space-y-3">
                    <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-white font-bold">Director Eyebrow</label>
                    <input type="text" name="about_eyebrow" value="{{ $settings['about_eyebrow'] ?? '' }}" 
                           class="w-full bg-charcoal/80 border border-white/10 p-4 text-sm text-white focus:border-gold outline-none transition-all placeholder:italic" />
                </div>
                <div class="md:col-span-2 space-y-3">
                    <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-white font-bold">Narrative Title (HTML Support)</label>
                    <div class="bg-white rounded-lg">
                        <div class="quill-editor-container h-48 text-black" data-target="about_title" data-quill-mode="inline"></div>
                    </div>
                    <input type="hidden" id="about_title" name="about_title" value="{{ $settings['about_title'] ?? '' }}">
                </div>
                <div class="md:col-span-2 space-y-3">
                    <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-white font-bold">Main Narrative (Paragraph 1)</label>
                    <div class="bg-white rounded-lg">
                        <div class="quill-editor-container h-48 text-black" data-target="about_body_1"></div>
                    </div>
                    <input type="hidden" id="about_body_1" name="about_body_1" value="{{ $settings['about_body_1'] ?? '' }}">
                </div>
                <div class="md:col-span-2 space-y-3">
                    <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-white font-bold">Secondary Narrative (Paragraph 2)</label>
                    <div class="bg-white rounded-lg">
                        <div class="quill-editor-container h-48 text-black" data-target="about_body_2"></div>
                    </div>
                    <input type="hidden" id="about_body_2" name="about_body_2" value="{{ $settings['about_body_2'] ?? '' }}">
                </div>
                <div class="md:col-span-2 space-y-3">
                    <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-white font-bold">Director Portrait Image</label>
                    <div class="flex items-center gap-6">
                        @php
                            $portraitImagePath = $settings['about_portrait_image'] ?? null;
                            $portraitImageExists = $portraitImagePath && \Illuminate\Support\Facades\Storage::disk('public')->exists(str_replace('/storage/', '', $portraitImagePath));
                        @endphp
                        @if($portraitImageExists)
                            <div class="w-20 h-20 rounded border border-white/10 overflow-hidden bg-charcoal/80">
                                <img src="{{ $portraitImagePath }}" class="w-full h-full object-cover" />
                            </div>
                        @else
                            <div class="w-20 h-20 rounded border border-white/10 bg-gradient-to-br from-charcoal to-graphite flex items-center justify-center">
                                <svg class="w-8 h-8 text-gold/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        @endif
                        <input type="file" name="about_portrait_image" class="text-xs text-ash file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[0.6rem] file:font-semibold file:bg-charcoal/10 file:text-gold hover:file:bg-white/10 cursor-pointer" />
                    </div>
                    @if($portraitImageExists)
                        <label class="flex items-center gap-2 text-xs text-ash/60 cursor-pointer hover:text-red-400 transition-colors">
                            <input type="checkbox" name="remove_about_portrait_image" value="1" class="w-4 h-4 rounded border-white/20 bg-charcoal/50 text-red-500 focus:ring-red-500/50 focus:ring-2">
                            <span>Remove current portrait image</span>
                        </label>
                    @endif
                </div>
            </div>
        </div>

        <!-- ── STUDIO STATISTICS ── -->
        <div class="glass p-8 md:p-10 border border-charcoal/10 dark:border-white/10 rounded-xl relative overflow-hidden group shadow-2xl">
            <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <h2 class="text-[0.65rem] tracking-[0.4em] uppercase text-gold font-bold mb-8 flex items-center gap-3">
                <span class="w-8 h-px bg-gold/30"></span> 03. Studio Statistics
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="space-y-3">
                    <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-silver font-bold">Years Experience</label>
                    <input type="text" name="stats_years" value="{{ $settings['stats_years'] ?? '' }}" 
                           class="w-full bg-charcoal/80 border border-white/10 p-4 text-xl font-serif text-gold focus:border-gold outline-none transition-all text-center" />
                </div>
                <div class="space-y-3">
                    <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-silver font-bold">Projects Delivered</label>
                    <input type="text" name="stats_projects" value="{{ $settings['stats_projects'] ?? '' }}" 
                           class="w-full bg-charcoal/80 border border-white/10 p-4 text-xl font-serif text-gold focus:border-gold outline-none transition-all text-center" />
                </div>
                <div class="space-y-3">
                    <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-silver font-bold">National Awards</label>
                    <input type="text" name="stats_awards" value="{{ $settings['stats_awards'] ?? '' }}" 
                           class="w-full bg-charcoal/80 border border-white/10 p-4 text-xl font-serif text-gold focus:border-gold outline-none transition-all text-center" />
                </div>
            </div>
        </div>

        <!-- ── CTA SECTION ── -->
        <div class="glass p-8 md:p-10 border border-charcoal/10 dark:border-white/10 rounded-xl relative overflow-hidden group shadow-2xl">
            <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
            <h2 class="text-[0.65rem] tracking-[0.4em] uppercase text-gold font-bold mb-8 flex items-center gap-3">
                <span class="w-8 h-px bg-gold/30"></span> 04. Engagement Narrative (CTA)
            </h2>

            <div class="space-y-8">
                <div class="space-y-3">
                    <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-silver font-bold">CTA Title</label>
                    <input type="text" name="cta_title" value="{{ $settings['cta_title'] ?? '' }}" 
                           class="w-full bg-charcoal/80 border border-white/10 p-4 font-serif text-2xl text-white focus:border-gold outline-none transition-all italic" />
                </div>
                <div class="space-y-3">
                    <label class="block text-[0.6rem] tracking-[0.4em] uppercase text-silver font-bold">CTA Subtext (Engagement Pitch)</label>
                    <div class="bg-white rounded-lg">
                        <div class="quill-editor-container h-32 text-black" data-target="cta_subtext" data-quill-mode="inline"></div>
                    </div>
                    <input type="hidden" id="cta_subtext" name="cta_subtext" value="{{ $settings['cta_subtext'] ?? '' }}">
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-10">
            <button type="submit" class="bg-gold text-black px-12 py-5 text-xs tracking-[0.4em] uppercase font-bold hover:bg-white transition-all shadow-xl shadow-gold/10">
                Seal Narrative →
            </button>
        </div>
    </form>
</div>
@endsection
