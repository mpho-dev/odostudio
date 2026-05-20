<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard — Odo Studio')</title>

    <!-- Cinematic Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-black text-ash font-sans overflow-hidden">
    <!-- Cinematic background for dashboard -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_30%,rgba(201,168,76,0.02)_0%,transparent_50%),radial-gradient(circle_at_80%_70%,rgba(201,168,76,0.02)_0%,transparent_50%)]"></div>
        <div class="absolute inset-0 film-grain opacity-[0.15]"></div>
    </div>

    <div class="relative z-10 flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
        <!-- Sidebar Navigation -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-72 shrink-0 flex flex-col border-r border-white/5 bg-graphite/40 backdrop-blur-xl transition-transform duration-300 md:relative md:translate-x-0">
            <!-- Mobile Close Button -->
            <button @click="sidebarOpen = false" class="absolute top-6 right-6 text-silver/60 hover:text-white md:hidden transition-colors">
                <svg class="w-6 h-6 border rounded p-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <!-- Logo area -->
            <div class="p-8 pb-12">
                <div class="flex items-center gap-3 group px-2">
                    <div class="w-10 h-10 bg-gold grid place-items-center text-black font-serif text-xl font-bold group-hover:scale-110 transition-transform duration-500 shadow-lg shadow-gold/20">O</div>
                    <div>
                        <h1 class="font-serif text-xl font-bold tracking-tight text-charcoal dark:text-white leading-none">Odo <em>Studio</em></h1>
                        <p class="text-[0.55rem] tracking-[0.4em] uppercase text-gold mt-1">Studio Command</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 overflow-y-auto px-6 space-y-10 custom-scrollbar">
                <!-- User Profile Summary -->
                @if(auth()->check())
                <div class="px-2">
                    <div class="p-4 bg-charcoal/5 dark:bg-white/5 border border-charcoal/10 dark:border-white/10 rounded-xl relative group overflow-hidden">
                        <div class="absolute inset-0 bg-gold opacity-0 group-hover:opacity-[0.03] transition-opacity duration-500"></div>
                        <p class="text-[0.55rem] tracking-widest uppercase text-ash/60 mb-1">Signed In As</p>
                        <p class="font-serif text-lg text-charcoal dark:text-white leading-tight mb-1">{{ auth()->user()->name }}</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach(auth()->user()->roles as $role)
                            <span class="text-[0.5rem] tracking-widest uppercase px-1.5 py-0.5 bg-gold/10 text-gold border border-gold/20 rounded-full">{{ str_replace('_', ' ', $role->name) }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Admin Group -->
                @if(auth()->check() && auth()->user()->hasRole('admin'))
                <div>
                    <h3 class="px-4 text-[0.6rem] tracking-[0.4em] uppercase text-gold/60 mb-4 font-bold">Studio Control</h3>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('admin.site-config') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-charcoal dark:text-silver hover:bg-gold/10 hover:text-gold transition-all group {{ request()->routeIs('admin.site-config') ? 'bg-gold/10 text-gold' : '' }}">
                                <svg class="w-5 h-5 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span class="tracking-wide">Brand Narrative</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('services.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-charcoal dark:text-silver hover:bg-gold/10 hover:text-gold transition-all group {{ request()->routeIs('services.*') ? 'bg-gold/10 text-gold' : '' }}">
                                <svg class="w-5 h-5 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="tracking-wide">Service Offerings</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('projects.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-charcoal dark:text-silver hover:bg-gold/10 hover:text-gold transition-all group {{ request()->routeIs('projects.*') ? 'bg-gold/10 text-gold' : '' }}">
                                <svg class="w-5 h-5 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                                </svg>
                                <span class="tracking-wide">Visual Portfolio</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('investment-tiers.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-charcoal dark:text-silver hover:bg-gold/10 hover:text-gold transition-all group {{ request()->routeIs('investment-tiers.*') ? 'bg-gold/10 text-gold' : '' }}">
                                <svg class="w-5 h-5 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="tracking-wide">Investment Collections</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('process-steps.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-charcoal dark:text-silver hover:bg-gold/10 hover:text-gold transition-all group {{ request()->routeIs('process-steps.*') ? 'bg-gold/10 text-gold' : '' }}">
                                <svg class="w-5 h-5 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                <span class="tracking-wide">Workflow Stages</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('testimonials.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-charcoal dark:text-silver hover:bg-gold/10 hover:text-gold transition-all group {{ request()->routeIs('testimonials.*') ? 'bg-gold/10 text-gold' : '' }}">
                                <svg class="w-5 h-5 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                                <span class="tracking-wide">Client Acclaim</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.email-config') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-charcoal dark:text-silver hover:bg-gold/10 hover:text-gold transition-all group {{ request()->routeIs('admin.email-config') ? 'bg-gold/10 text-gold' : '' }}">
                                <svg class="w-5 h-5 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="tracking-wide">Comms Infrastructure</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.health-check') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-charcoal dark:text-silver hover:bg-gold/10 hover:text-gold transition-all group {{ request()->routeIs('admin.health-check') ? 'bg-gold/10 text-gold' : '' }}">
                                <svg class="w-5 h-5 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span class="tracking-wide">Studio Health</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.documentation') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-charcoal dark:text-silver hover:bg-gold/10 hover:text-gold transition-all group {{ request()->routeIs('admin.documentation') ? 'bg-gold/10 text-gold' : '' }}">
                                <svg class="w-5 h-5 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <span class="tracking-wide">Documentation</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-charcoal dark:text-silver hover:bg-gold/10 hover:text-gold transition-all group {{ request()->routeIs('admin.users.*') ? 'bg-gold/10 text-gold' : '' }}">
                                <svg class="w-5 h-5 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <span class="tracking-wide">Crew & Talent</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.equipment.items.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-charcoal dark:text-silver hover:bg-gold/10 hover:text-gold transition-all group {{ request()->routeIs('admin.equipment.*') ? 'bg-gold/10 text-gold' : '' }}">
                                <svg class="w-5 h-5 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span class="tracking-wide">Equipment Inventory</span>
                            </a>
                        </li>
                    </ul>
                </div>
                @endif

                <!-- Manager Group -->

                {{-- photographers (and admins) still need the media link even though the rest of the
                     administration area is admin‑only --}}
                 @if(auth()->check() && auth()->user()->hasAnyRole(['admin','crew']))
                <div>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('admin.media.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-charcoal dark:text-silver hover:bg-gold/10 hover:text-gold transition-all group {{ request()->routeIs('admin.media.index') ? 'bg-gold/10 text-gold' : '' }}">
                                <svg class="w-5 h-5 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="tracking-wide">Rushes & Assets</span>
                            </a>
                        </li>
                    </ul>
                </div>
                @endif

                <!-- Manager Group -->
                @if(auth()->check() && auth()->user()->hasRole('manager'))
                <div>
                    <h3 class="px-4 text-[0.6rem] tracking-[0.4em] uppercase text-gold/60 mb-4 font-bold">Production Logistics</h3>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('manager.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-charcoal dark:text-silver hover:bg-gold/10 hover:text-gold transition-all group {{ request()->routeIs('manager.dashboard') ? 'bg-gold/10 text-gold' : '' }}">
                                <svg class="w-5 h-5 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span class="tracking-wide">Production Overview</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('requests.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-charcoal dark:text-silver hover:bg-gold/10 hover:text-gold transition-all group {{ request()->routeIs('requests.index') ? 'bg-gold/10 text-gold' : '' }}">
                                <svg class="w-5 h-5 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="tracking-wide">Casting & Enquiries</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('bookings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-charcoal dark:text-silver hover:bg-gold/10 hover:text-gold transition-all group {{ request()->routeIs('bookings.index') ? 'bg-gold/10 text-gold' : '' }}">
                                <svg class="w-5 h-5 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                <span class="tracking-wide">Active Shoots</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('invoices.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-charcoal dark:text-silver hover:bg-gold/10 hover:text-gold transition-all group {{ request()->routeIs('invoices.index') ? 'bg-gold/10 text-gold' : '' }}">
                                <svg class="w-5 h-5 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                <span class="tracking-wide">Billing & Retainers</span>
                            </a>
                        </li>
                    </ul>
                </div>
                @endif

                <!-- Photographer Group -->
                @if(auth()->check() && auth()->user()->hasRole('crew'))
                <div>
                    <h3 class="px-4 text-[0.6rem] tracking-[0.4em] uppercase text-gold/60 mb-4 font-bold">Field Operations</h3>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('photographer.calendar') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-charcoal dark:text-silver hover:bg-gold/10 hover:text-gold transition-all group {{ request()->routeIs('photographer.calendar') ? 'bg-gold/10 text-gold' : '' }}">
                                <svg class="w-5 h-5 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="tracking-wide">Shooting Schedule</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('bookings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-charcoal dark:text-silver hover:bg-gold/10 hover:text-gold transition-all group {{ request()->routeIs('bookings.index') ? 'bg-gold/10 text-gold' : '' }}">
                                <svg class="w-5 h-5 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <span class="tracking-wide">My Call Sheets</span>
                            </a>
                        </li>
                    </ul>
                </div>
                @endif
            </nav>

            <!-- Logout Section -->
            <div class="p-6 border-t border-white/5 space-y-2">
                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-4 py-3 w-full rounded-lg text-sm text-charcoal/60 dark:text-silver/60 hover:bg-gold/10 hover:text-gold transition-all group">
                    <svg class="w-5 h-5 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    <span class="tracking-wide">View Site</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-3 w-full rounded-lg text-sm text-charcoal/60 dark:text-silver/60 hover:bg-red-500/10 hover:text-red-500 transition-all group">
                        <svg class="w-5 h-5 opacity-60 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="tracking-wide">End Session</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Sidebar Overlay (mobile only) -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak class="fixed inset-0 bg-black/60 z-40 md:hidden backdrop-blur-sm"></div>

        <!-- Main Workspace -->
        <main class="flex-1 flex flex-col min-h-[calc(100vh-80px)] md:min-h-screen bg-white/30 dark:bg-black/10 backdrop-blur-sm relative glass-edge-l">
            <!-- Global Stage Header -->
            <header class="h-24 flex items-center justify-between px-6 md:px-12 border-b border-iron/10 dark:border-white/10 sticky top-0 z-40 bg-paper/50 dark:bg-black/50 backdrop-blur-xl shrink-0">
                <div class="flex items-center gap-4">
                    <!-- Mobile Menu Button -->
                    <button @click="sidebarOpen = true" class="p-2 text-silver/60 hover:text-white md:hidden transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </button>

                    <h2 class="font-serif text-xl md:text-3xl font-light text-charcoal dark:text-white tracking-tight italic truncate">
                        @yield('header', 'Workspace')
                    </h2>
                </div>

                <div class="flex items-center gap-6">
                    <div class="h-8 w-px bg-iron/20 dark:bg-white/10 hidden sm:block"></div>
                    <div class="text-right hidden sm:block">
                        <p class="text-[0.5rem] tracking-[0.3em] uppercase text-gold font-bold">Current Sector</p>
                        <p class="text-xs text-charcoal dark:text-silver font-medium">{{ Route::currentRouteName() }}</p>
                    </div>
                </div>
            </header>

            <!-- Playback Area / Content -->
            <div class="flex-1 overflow-y-auto custom-scrollbar relative">
                <!-- Notifications -->
                @if (session('success'))
                <div class="max-w-4xl mx-auto mt-6 p-5 bg-gold/10 border-l-2 border-gold text-gold rounded-r-xl flex items-center gap-4 animate-hero-in">
                    <span class="text-xl">✨</span>
                    <span class="text-sm font-medium tracking-wide">{{ session('success') }}</span>
                </div>
                @endif

                @if (session('error'))
                <div class="max-w-4xl mx-auto mt-6 p-5 bg-red-500/10 border-l-2 border-red-500 text-red-500 rounded-r-xl flex items-center gap-4 animate-hero-in">
                    <span class="text-xl">⚠️</span>
                    <span class="text-sm font-medium tracking-wide">{{ session('error') }}</span>
                </div>
                @endif

                <div class="max-w-7xl mx-auto p-6 md:p-12 animate-hero-in" style="animation-delay: 200ms;">
                    @yield('content')
                </div>
            </div>
            
            <!-- Thin Footer -->
            <footer class="py-3 px-6 border-t border-iron/10 dark:border-white/10 bg-paper/80 dark:bg-black/80 backdrop-blur-xl shrink-0">
                <div class="max-w-7xl mx-auto flex items-center justify-between text-[0.55rem] tracking-wider text-ash/60">
                    <span>{{ config('app.name', 'Odo Studio') }} &copy; {{ date('Y') }}</span>
                    <span class="hidden sm:inline">Production Command v1.0</span>
                </div>
            </footer>
        </main>
    </div>

    <!-- Script removed: Global Dark Theme locked -->
    @stack('scripts')
    <script>
        // Intersection Observer for reveal effect
        const revealObserver = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    revealObserver.unobserve(e.target);
                }
            });
        }, {
            threshold: 0.15
        });

        document.querySelectorAll('.reveal').forEach(el => {
            revealObserver.observe(el);
        });

        // Relocate fixed-position modals to document.body
        // This escapes the <main> backdrop-filter containing block
        document.querySelectorAll('div[role="dialog"]').forEach(el => {
            if (el.classList.contains('fixed')) {
                document.body.appendChild(el);
            }
        });
    </script>
</body>

</html>
