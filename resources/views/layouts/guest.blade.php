<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Odo Studio — Photography & Videography')</title>

    <!-- Meta Tags -->
    <meta name="description" content="@yield('description', 'Odo Studio: Premier photography and videography specializing in cinematic weddings, brand narratives, and timeless portraits.')">
    <meta name="keywords" content="@yield('keywords', 'photography, videography, wedding photography, brand film, portfolio, Odo Studio, South Africa')">
    <meta name="author" content="Odo Studio">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Odo Studio — Photography & Videography')">
    <meta property="og:description" content="@yield('description', 'Odo Studio: Premier photography and videography specializing in cinematic weddings, brand narratives, and timeless portraits.')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', 'Odo Studio — Photography & Videography')">
    <meta property="twitter:description" content="@yield('description', 'Odo Studio: Premier photography and videography specializing in cinematic weddings, brand narratives, and timeless portraits.')">
    <meta property="twitter:image" content="@yield('og_image', asset('images/og-default.jpg'))">

    @yield('meta')

    <!-- Schema.org LocalBusiness Structured Data -->
    @php
        $schema = json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => 'Odo Studio',
            'image' => asset('images/og-default.jpg'),
            'description' => 'Premier cinematic photography and videography studio in South Africa specializing in weddings, brand narratives, and timeless portraits.',
            'url' => url('/'),
            'telephone' => '+27 (your-number)',
            'email' => 'hello@odostudio.co.za',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'Mpumalanga',
                'addressRegion' => 'Mpumalanga',
                'postalCode' => '',
                'addressCountry' => 'ZA'
            ],
            'areaServed' => ['ZA', 'Worldwide'],
            'priceRange' => '$$$',
            'sameAs' => [
                'https://instagram.com/odostudio',
                'https://pinterest.com/odostudio',
                'https://vimeo.com/odostudio'
            ],
            'aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => '5',
                'ratingCount' => '12'
            ]
        ]);
    @endphp
    <script type="application/ld+json">
    {!! $schema !!}
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Outfit:wght@200;300;400;500&display=swap" rel="stylesheet">

    <!-- Styles/Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="bg-black text-cream font-sans font-light overflow-x-hidden selection:bg-gold selection:text-black transition-colors duration-500">
    <!-- Custom Cursor Dot -->
    <div id="cursorDot" class="cursor-dot hidden md:block"></div>

    <!-- Navigation -->
    <nav x-data="{ open: false, scrolled: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 10) ? true : false"
         :class="{ 'scrolled': scrolled }"
         x-init="console.log('Navbar initialized, scrolled state:', scrolled)"
         class="nav">

        <div class="nav-inner">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="nav-logo">
                ODO <span>STUDIO</span>
            </a>

        <!-- Desktop Menu -->
        <ul class="nav-links">
            @php
                $showHomeButton = \App\Models\SiteSetting::isHomeButtonEnabled() && (
                    request()->routeIs('portfolio') ||
                    request()->routeIs('portfolio.*') ||
                    request()->routeIs('contact.*') ||
                    request()->routeIs('contact.form')
                );
            @endphp
            @if($showHomeButton)
            <li>
                <a href="{{ route('home') }}" class="nav-link" aria-label="Home" title="Home">Home</a>
            </li>
            @endif
            <li><a href="{{ route('home') }}#about" class="nav-link">About</a></li>
            <li><a href="{{ route('home') }}#services" class="nav-link">Services</a></li>
            <li><a href="{{ route('portfolio') }}" class="nav-link">Portfolio</a></li>
            <li><a href="{{ route('contact.form') }}" class="nav-link">Contact</a></li>
        </ul>

            <!-- Desktop Actions -->
            <div class="hidden md:flex items-center gap-6">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-[0.68rem] tracking-widest uppercase text-gold hover:text-white transition-colors">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-[0.68rem] tracking-widest uppercase text-ash hover:text-white transition-colors">Login</a>
                @endauth
                <a href="{{ route('contact.form') }}" class="nav-cta">
                    Book A Session
                </a>
            </div>

            <!-- Mobile Hamburger Button -->
            <div class="md:hidden flex items-center relative z-50">
                <button @click="open = !open" type="button" class="text-gold hover:text-white focus:outline-none transition-colors" aria-label="Toggle menu">
                    <svg x-show="!open" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="open" style="display: none;" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div x-show="open" style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="absolute top-full left-0 right-0 bg-black/95 backdrop-blur-xl border-b border-white/5 shadow-2xl md:hidden overflow-hidden">

            <div class="px-6 py-8 flex flex-col gap-6">
                <ul class="flex flex-col gap-6 list-none text-[0.8rem] tracking-[0.22em] uppercase">
                    @php
                        $showHomeLink = \App\Models\SiteSetting::isHomeButtonEnabled() && (
                            request()->routeIs('portfolio') ||
                            request()->routeIs('portfolio.*') ||
                            request()->routeIs('contact.*') ||
                            request()->routeIs('contact.form')
                        );
                    @endphp
                    @if($showHomeLink)
                    <li><a href="{{ route('home') }}" @click="open = false" class="block text-white hover:text-gold transition-colors">Home</a></li>
                    @endif
                    <li><a href="{{ route('home') }}#about" @click="open = false" class="block text-white hover:text-gold transition-colors">About</a></li>
                    <li><a href="{{ route('home') }}#services" @click="open = false" class="block text-white hover:text-gold transition-colors">Services</a></li>
                    <li><a href="{{ route('portfolio') }}" @click="open = false" class="block text-white hover:text-gold transition-colors">Portfolio</a></li>
                    <li><a href="{{ route('contact.form') }}" @click="open = false" class="block text-white hover:text-gold transition-colors">Contact</a></li>
                </ul>

                <hr class="border-white/10 w-full" />

                <div class="flex flex-col gap-6">
                    @auth
                        <a href="{{ route('dashboard') }}" @click="open = false" class="block text-[0.8rem] tracking-widest uppercase text-gold hover:text-white transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" @click="open = false" class="block text-[0.8rem] tracking-widest uppercase text-ash hover:text-white transition-colors">Login</a>
                    @endauth
                    <a href="{{ route('contact.form') }}" @click="open = false" class="inline-block text-center text-[0.75rem] tracking-[0.2em] uppercase text-black bg-gold px-6 py-3 hover:bg-white transition-all duration-250 shadow-[0_0_15px_rgba(201,168,76,0.3)]">
                        Book A Session
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-logo">
            ODO <span class="text-gold">STUDIO</span>
        </div>
        <div class="footer-copy">
            &copy; {{ date('Y') }} Odo Group Pty Ltd. All rights reserved.
        </div>
        <div class="footer-socials">
            <a href="#" class="footer-social-link">Instagram</a>
            <a href="#" class="footer-social-link">Pinterest</a>
            <a href="#" class="footer-social-link">Vimeo</a>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop" aria-label="Back to top" class="fixed right-8 z-40 hidden w-12 h-12 bg-gold text-black rounded-full flex items-center justify-center hover:bg-white transition-all duration-250 shadow-lg" style="bottom: 32px;" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>

    <!-- Scripts -->
    <script>
        // Back to Top Button (stays above footer)
        const backToTopButton = document.getElementById('backToTop');
        const footer = document.querySelector('footer');
        const defaultBottom = 32;

        function updateBackToTopPosition() {
            if (!backToTopButton || !footer) return;

            const footerRect = footer.getBoundingClientRect();
            const viewportHeight = window.innerHeight;

            if (footerRect.top < viewportHeight) {
                const overlapAmount = viewportHeight - footerRect.top;
                backToTopButton.style.bottom = `${defaultBottom + overlapAmount}px`;
            } else {
                backToTopButton.style.bottom = `${defaultBottom}px`;
            }
        }

        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.remove('hidden');
                updateBackToTopPosition();
            } else {
                backToTopButton.classList.add('hidden');
                backToTopButton.style.bottom = `${defaultBottom}px`;
            }
        });

        window.addEventListener('resize', updateBackToTopPosition);

        // Custom Cursor Dot
        const dot = document.getElementById('cursorDot');
        if (dot) {
            document.addEventListener('mousemove', e => {
                dot.style.left = e.clientX + 'px';
                dot.style.top  = e.clientY + 'px';
            });

            // Interaction feedback
            document.querySelectorAll('a, button, .service-card, .pkg-card, .testimonial-card').forEach(el => {
                el.addEventListener('mouseenter', () => dot.style.transform = 'translate(-50%, -50%) scale(3)');
                el.addEventListener('mouseleave', () => dot.style.transform = 'translate(-50%, -50%) scale(1)');
            });
        }

        // Intersection Observer for reveal effect
        const revealObserver = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    revealObserver.unobserve(e.target);
                }
            });
        }, { threshold: 0.15 });

        document.querySelectorAll('.reveal').forEach(el => {
            revealObserver.observe(el);
        });
    </script>
</body>
</html>
