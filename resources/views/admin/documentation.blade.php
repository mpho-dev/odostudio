@extends('layouts.app')

@section('title', 'Documentation — Odo Studio')

@section('header', 'Documentation')

@section('content')
<div class="space-y-12 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-end gap-6 reveal">
        <div>
            <div class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
                <span>Resources</span>
                <div class="h-px w-16 bg-gold/30"></div>
            </div>
            <h1 class="font-serif text-4xl text-charcoal dark:text-white leading-tight">Studio <em>Documentation</em></h1>
            <p class="text-xs tracking-[0.3em] uppercase text-ash/60 dark:text-ash/60 mt-2 italic">Comprehensive guides for Odo Studio</p>
        </div>
    </div>

    <!-- Getting Started Card -->
    <div class="reveal">
        <div class="glass group relative rounded-2xl border border-charcoal/10 dark:border-white/10 overflow-hidden transition-all duration-500 hover:border-gold/30 hover:shadow-2xl hover:shadow-gold/5">
            <div class="absolute inset-0 bg-gradient-to-br from-gold/5 via-transparent to-transparent"></div>
            <div class="relative p-8 md:p-10">
                <div class="flex flex-col lg:flex-row lg:items-center gap-8">
                    <!-- Visual Illustration -->
                    <div class="shrink-0">
                        <div class="w-32 h-32 relative">
                            <!-- Central hub -->
                            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-12 h-12 bg-gold rounded-xl flex items-center justify-center shadow-lg shadow-gold/20">
                                <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <!-- Orbiting nodes -->
                            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-8 h-8 bg-charcoal/80 border border-white/10 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-gold/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div class="absolute bottom-2 left-2 w-7 h-7 bg-charcoal/80 border border-white/10 rounded-lg flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="absolute bottom-2 right-2 w-7 h-7 bg-charcoal/80 border border-white/10 rounded-lg flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="absolute top-2 right-2 w-7 h-7 bg-charcoal/80 border border-white/10 rounded-lg flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <!-- Connecting lines (CSS) -->
                            <svg class="absolute inset-0 w-full h-full opacity-20">
                                <line x1="50%" y1="50%" x2="50%" y2="12%" stroke="currentColor" stroke-width="1" class="text-gold"/>
                                <line x1="50%" y1="50%" x2="22%" y2="75%" stroke="currentColor" stroke-width="1" class="text-gold"/>
                                <line x1="50%" y1="50%" x2="78%" y2="75%" stroke="currentColor" stroke-width="1" class="text-gold"/>
                                <line x1="50%" y1="50%" x2="78%" y2="22%" stroke="currentColor" stroke-width="1" class="text-gold"/>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="px-3 py-1 bg-gold/10 text-gold text-[0.6rem] tracking-widest uppercase rounded-full border border-gold/20">Start Here</span>
                            <span class="text-[0.65rem] tracking-widest uppercase text-ash/40">5 min read</span>
                        </div>
                        <h2 class="font-serif text-2xl text-white mb-3">Getting Started with Odo Studio</h2>
                        <p class="text-sm text-ash/60 leading-relaxed mb-6 max-w-2xl">Welcome to Odo Studio! This guide walks you through the initial setup, key concepts, and how to navigate the system. Perfect for new team members and those wanting a quick overview.</p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('docs.show', 'admin') }}" class="group relative px-6 py-3 bg-gold text-black text-[0.65rem] tracking-[0.3em] uppercase font-bold hover:bg-white transition-all duration-500 overflow-hidden">
                                <span class="relative z-10">Begin Guide</span>
                                <div class="absolute inset-0 bg-white translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-500"></div>
                            </a>
                            <a href="#quick-links" class="px-6 py-3 border border-charcoal/20 dark:border-white/10 text-silver text-[0.65rem] tracking-[0.3em] uppercase font-bold hover:border-gold/30 hover:text-gold transition-all duration-500">
                                Quick Links ↓
                            </a>
                        </div>
                    </div>
        </div>
    </div>
        </div>
    </div>

    <!-- Documentation Cards Grid -->
    <div class="reveal" style="animation-delay: 50ms">
        <div class="flex items-center gap-4 mb-6">
            <span class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
                <span>User Guides</span>
                <div class="h-px w-16 bg-gold/30"></div>
            </span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
            // Documentation array is now passed from controller as $docs
            @endphp

            @foreach($docs as $key => $doc)
            <a href="{{ route('docs.show', $key) }}" 
               class="group relative glass rounded-xl border border-charcoal/10 dark:border-white/10 overflow-hidden transition-all duration-500 hover:border-gold/30 hover:shadow-2xl hover:shadow-gold/5 shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/40 group-hover:w-12 group-hover:h-12 transition-all duration-500"></div>
                <!-- Visual Illustration Header -->
                <div class="h-24 bg-gradient-to-br from-charcoal/30 to-charcoal/10 dark:from-white/5 dark:to-transparent relative overflow-hidden">
                    <!-- Abstract visual based on type -->
                    @if($doc['visual'] === 'admin')
                        <!-- Admin: Shield + Settings -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-16 h-16 border-2 border-gold/30 rounded-xl flex items-center justify-center relative">
                                <svg class="w-8 h-8 text-gold/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                <div class="absolute -top-1 -right-1 w-5 h-5 bg-gold/20 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                                </div>
                            </div>
                        </div>
                    @elseif($doc['visual'] === 'manager')
                        <!-- Manager: Chart + Documents -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="flex items-end gap-1 h-12">
                                <div class="w-3 h-6 bg-gold/30 rounded-t"></div>
                                <div class="w-3 h-9 bg-gold/40 rounded-t"></div>
                                <div class="w-3 h-5 bg-gold/20 rounded-t"></div>
                                <div class="w-3 h-11 bg-gold/50 rounded-t"></div>
                                <div class="w-3 h-7 bg-gold/30 rounded-t"></div>
                            </div>
                            <div class="absolute bottom-2 right-2 w-8 h-8 border border-gold/20 rounded flex items-center justify-center">
                                <svg class="w-4 h-4 text-gold/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                        </div>
                    @elseif($doc['visual'] === 'photographer')
                        <!-- Photographer: Camera + Calendar -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="relative">
                                <div class="w-14 h-10 bg-charcoal/50 border border-white/10 rounded-lg flex items-center justify-center">
                                    <div class="w-8 h-6 bg-gold/20 rounded flex items-center justify-center">
                                        <div class="w-3 h-3 bg-gold/40 rounded-full"></div>
                                    </div>
                                </div>
                                <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-charcoal/50 border border-white/10 rounded flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gold/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            </div>
                        </div>
                    @elseif($doc['visual'] === 'public')
                        <!-- Public: Globe -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-14 h-14 border-2 border-gold/20 rounded-full flex items-center justify-center relative">
                                <svg class="w-8 h-8 text-gold/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                <div class="absolute w-full h-[1px] bg-gold/20"></div>
                                <div class="absolute h-full w-[1px] bg-gold/20"></div>
                            </div>
                        </div>
                    @elseif($doc['visual'] === 'architecture')
                        <!-- Architecture: Layers -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="space-y-1">
                                <div class="w-16 h-2 bg-gold/20 rounded"></div>
                                <div class="w-14 h-2 bg-gold/30 rounded ml-2"></div>
                                <div class="w-12 h-2 bg-gold/40 rounded ml-4"></div>
                                <div class="w-10 h-2 bg-gold/20 rounded ml-6"></div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Content -->
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-gold/10 flex items-center justify-center">
                                <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $doc['icon'] }}"/>
                                </svg>
                            </div>
                            <span class="text-[0.55rem] tracking-widest uppercase text-ash/40">{{ $doc['readTime'] }} read</span>
                        </div>
                    </div>
                    <h3 class="text-white font-bold text-base mb-2 group-hover:text-gold transition-colors">{{ $doc['title'] }}</h3>
                    <p class="text-xs text-ash/60 leading-relaxed">{{ $doc['description'] }}</p>
                    
                    <div class="mt-4 flex items-center gap-2 text-xs text-gold opacity-0 group-hover:opacity-100 transition-opacity">
                        <span>Read guide</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    @if(auth()->user() && auth()->user()->hasRole('admin'))
    <!-- Quick Links Section -->
    <div id="quick-links" class="reveal" style="animation-delay: 100ms">
        <div class="flex items-center gap-4 mb-6">
            <span class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
                <span>Quick Links</span>
                <div class="h-px w-16 bg-gold/30"></div>
            </span>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
            $quickLinks = [
                [
                    'title' => 'Site Settings',
                    'description' => 'Brand, hero, About page',
                    'route' => 'admin.site-config',
                    'icon' => 'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z',
                ],
                [
                    'title' => 'Services',
                    'description' => 'Manage offerings',
                    'route' => 'services.index',
                    'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
                ],
                [
                    'title' => 'Media Library',
                    'description' => 'Upload & manage assets',
                    'route' => 'admin.media.index',
                    'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z',
                ],
                [
                    'title' => 'System Health',
                    'description' => 'Check vitals',
                    'route' => 'admin.health-check',
                    'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                ],
            ];
            @endphp
            
            @foreach($quickLinks as $link)
            <a href="{{ route($link['route']) }}" 
               class="group glass p-5 rounded-xl border border-charcoal/10 dark:border-white/10 hover:border-gold/30 hover:shadow-lg hover:shadow-gold/5 transition-all duration-500">
                <div class="w-10 h-10 rounded-lg bg-gold/10 flex items-center justify-center mb-3 group-hover:bg-gold/20 transition-colors">
                    <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $link['icon'] }}"/>
                    </svg>
                </div>
                <h4 class="text-white font-bold text-sm mb-1 group-hover:text-gold transition-colors">{{ $link['title'] }}</h4>
                <p class="text-xs text-ash/50">{{ $link['description'] }}</p>
            </a>
            @endforeach
        </div>
    </div>

    <!-- Features Overview -->
    <div class="reveal" style="animation-delay: 150ms">
        <div class="glass rounded-xl p-8 border border-charcoal/10 dark:border-white/10">
            <div class="flex items-center gap-4 mb-6">
                <span class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase text-gold mb-4">
                    <span>All Features</span>
                    <div class="h-px w-16 bg-gold/30"></div>
                </span>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @php
                $features = [
                    ['name' => 'Dashboard', 'route' => 'home', 'doc' => 'admin'],
                    ['name' => 'Booking Requests', 'route' => 'requests.index', 'doc' => 'manager'],
                    ['name' => 'Bookings', 'route' => 'bookings.index', 'doc' => 'manager'],
                    ['name' => 'Invoices', 'route' => 'invoices.index', 'doc' => 'manager'],
                    ['name' => 'Users', 'route' => 'admin.users.index', 'doc' => 'admin'],
                    ['name' => 'Services', 'route' => 'services.index', 'doc' => 'admin'],
                    ['name' => 'Projects', 'route' => 'projects.index', 'doc' => 'admin'],
                    ['name' => 'Media', 'route' => 'admin.media.index', 'doc' => 'admin'],
                    ['name' => 'Testimonials', 'route' => 'testimonials.index', 'doc' => 'admin'],
                    ['name' => 'Process Steps', 'route' => 'process-steps.index', 'doc' => 'admin'],
                    ['name' => 'Investment', 'route' => 'investment-tiers.index', 'doc' => 'admin'],
                    ['name' => 'Email Config', 'route' => 'admin.email-config', 'doc' => 'admin'],
                ];
                @endphp
                
                @foreach($features as $feature)
                <a href="{{ route($feature['route']) }}" class="group flex items-center gap-3 p-3 rounded-lg border border-charcoal/10 dark:border-white/10 hover:border-gold/30 hover:bg-gold/5 transition-all duration-300">
                    <div class="w-8 h-8 rounded bg-gold/5 flex items-center justify-center shrink-0 group-hover:bg-gold/10 transition-colors">
                        <svg class="w-4 h-4 text-gold/60 group-hover:text-gold transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <span class="text-xs text-ash/70 group-hover:text-white transition-colors truncate">{{ $feature['name'] }}</span>
                </a>
                @endforeach
            </div>
            
            <p class="text-xs text-ash/40 mt-6 text-center">Click any feature to access it directly. Refer to the guides above for detailed documentation.</p>
        </div>
    </div>
    @endif
</div>
@endsection
