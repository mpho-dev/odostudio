@extends('layouts.guest')

@section('title', 'Odo Studio — Cinematic Photography & Videography')

@section('description', 'Boutique cinematic photography and videography studio in South Africa. Capturing the unspoken moments through high-end storytelling and light.')
@section('keywords', 'cinema studio, wedding videographer South Africa, luxury photography, brand film studio, odo studio')

@section('content')
@include('components.hero')

<!-- ── ABOUT SECTION ── -->
<section id="about" class="about-full">
    <div class="about-wrap">
        <div class="about-portrait">
            <div class="portrait-frame">
                @if(isset($settings['about_portrait_image']))
                <div class="absolute inset-0 bg-cover bg-center grayscale brightness-75 transition-all duration-700 hover:grayscale-0 hover:brightness-100" style="background-image: url('{{ $settings['about_portrait_image'] }}');" role="img" aria-label="Odo Studio Director of Photography — Cinematic visual storyteller"></div>
                @else
                <div class="portrait-silhouette" role="img" aria-label="Odo Studio Director portrait silhouette"></div>
                @endif

                <div class="portrait-corner tl"></div>
                <div class="portrait-corner tr"></div>
                <div class="portrait-corner bl"></div>
                <div class="portrait-corner br"></div>

                <div class="portrait-label">{{ $settings['about_eyebrow'] ?? 'Director of Photography' }}</div>
            </div>
        </div>

        <div class="about-text-block">
            <div class="section-label">
                <span>{{ $settings['about_narrative_eyebrow'] ?? 'The Narrative' }}</span>
            </div>
            <h2 class="section-title">
                {!! $settings['about_title'] ?? 'Capturing the <em>unspoken</em> moments.' !!}
            </h2>
            <p>{!! $settings['about_body_1'] ?? 'Based in the heart of Mpumalanga, Odo Studio is a boutique creative house dedicated to the art of visual storytelling.' !!}</p>
                <p>{!! $settings['about_body_2'] ?? 'Our approach is rooted in cinematic principles—understanding how light, composition, and timing coalesce to create emotional resonance.' !!}</p>

            <div class="about-stats">
                <div>
                    <div class="stat-num">{{ $settings['stats_years'] ?? '8+' }}</div>
                    <div class="stat-label">Years Experience</div>
                </div>
                <div>
                    <div class="stat-num">{{ $settings['stats_projects'] ?? '450+' }}</div>
                    <div class="stat-label">Projects Delivered</div>
                </div>
                <div>
                    <div class="stat-num">{{ $settings['stats_awards'] ?? '12' }}</div>
                    <div class="stat-label">National Awards</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SERVICES ── -->
<section id="services" class="services-full">
    <div class="services-inner">
        <div class="section-label">Our Craft</div>
        <h2 class="section-title">
            Services for the <em>discerning</em>.
        </h2>

        <div class="services-grid reveal">
            @forelse($services ?? [] as $index => $service)
            <div class="service-card">
                <div class="service-number">
                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                </div>
                @if($service->icon)
                <div class="service-icon text-2xl mb-4">{{ $service->icon }}</div>
                @endif
                <h3 class="service-name">{{ $service->title }}</h3>
                <p class="service-desc">{{ $service->description }}</p>

                <div class="service-price">
                    From R {{ number_format($service->starting_price, 2) }}
                </div>

                @if($service->features && count($service->features) > 0)
                <ul class="service-features">
                    @foreach($service->features as $feature)
                    <li>{{ $feature }}</li>
                    @endforeach
                </ul>
                @endif
            </div>
            @empty
            <div class="col-span-3 p-12 text-center text-silver italic font-serif">
                Our collections are being updated. Please contact us for a custom quote.
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- ── PROCESS SECTION ── -->
@include('components.process-section')

<!-- ── INVESTMENT TIERS ── -->
<section class="packages-full" id="packages">
    <div class="packages-inner">
        <div class="section-label">Packages</div>
        <h2 class="section-title">Investment tiers</h2>

        @if(($investmentTiers ?? collect())->isEmpty())
        <div class="p-16 text-center border border-iron text-ash/40 italic font-serif reveal">
            Our investment tiers are being finalised. Please reach out for a custom quote.
        </div>
        @else
        <div class="packages-grid reveal">
            @foreach($investmentTiers as $tier)
            <div class="pkg-card {{ $tier->is_featured ? 'featured' : '' }}">
                @if($tier->is_featured && $tier->badge_label)
                <div class="pkg-badge">{{ $tier->badge_label }}</div>
                @endif

                <div class="pkg-tier">{{ $tier->tier_label }}</div>
                <h3 class="pkg-name">{{ $tier->name }}</h3>

                <div class="pkg-price">
                    R {{ number_format($tier->price, 0, '.', ',') }}
                    @if($tier->price_suffix)
                    <span>{{ $tier->price_suffix }}</span>
                    @endif
                </div>

                <div class="pkg-divider"></div>

                @if($tier->features)
                <ul class="pkg-features">
                    @foreach($tier->features as $feature)
                    <li>{{ $feature }}</li>
                    @endforeach
                </ul>
                @endif

                <a href="{{ route('contact.form') }}" class="pkg-btn">
                    Enquire Now
                </a>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

<!-- ── TESTIMONIALS ── -->
<section class="testimonials-full">
    <div class="testimonials-inner">
        <div class="section-label">Testimonials</div>
        <h2 class="section-title">What clients <em>say</em></h2>

        @if(($testimonials ?? collect())->isEmpty())
        <div class="p-16 text-center border border-iron text-ash/40 italic font-serif reveal">
            Client testimonials will appear here once you begin publishing them.
        </div>
        @else
        <div class="testimonials-track reveal">
            @foreach($testimonials as $testimonial)
                <div class="testimonial-card group">
                    <div class="absolute top-0 left-0 w-8 h-8 border-t border-l border-gold/30 group-hover:w-14 group-hover:h-14 transition-all duration-500"></div>
                    <div class="absolute bottom-0 right-0 w-8 h-8 border-b border-r border-gold/30 group-hover:w-14 group-hover:h-14 transition-all duration-500"></div>
                <div class="testimonial-stars">
                    {{ str_repeat('★', $testimonial->rating) }}{{ str_repeat('☆', 5 - $testimonial->rating) }}
                </div>
                <p class="testimonial-text">
                    {{ stripslashes($testimonial->quote) }}
                </p>
                <div class="testimonial-author">
                    <div class="author-avatar">
                        {{ stripslashes($testimonial->client_initials ?? substr($testimonial->client_name, 0, 1)) }}
                    </div>
                    <div class="author-info">
                        <div class="author-name">
                            {{ stripslashes($testimonial->client_name) }}
                        </div>
                        @if($testimonial->event_label)
                        <div class="author-event">{{ $testimonial->event_label }}</div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

<!-- ── CTA ── -->
<section class="py-24 text-center">
    <div class="max-w-3xl mx-auto px-6">
        <h2 class="section-title mb-8">{!! $settings['cta_title'] ?? 'Ready to tell your story?' !!}</h2>
        <div class="text-ash/70 dark:text-ash mb-10 space-y-4">
            {!! $settings['cta_subtext'] ?? '<p>We are currently taking bookings for late 2026. Secure your date with the team today.</p>' !!}
        </div>
        <a href="{{ route('contact.form') }}" class="pkg-btn inline-block px-10">
            Book a Session →
        </a>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Handle card hover interactivity for cursor scaling (already handled globally in guest layout for common classes)
    // We can add specific ones here if needed, but for now guest layout covers it.
</script>
@endpush
