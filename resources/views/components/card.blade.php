{{--
<x-card>
    Basic card content
</x-card>

<x-card variant="glass" hoverable>
    Glass card with hover effect
</x-card>

<x-card variant="flat" class="p-6">
    Flat card with custom padding
</x-card>

<x-card variant="service" :number="'01'" :icon="'heroicon-o-camera'" title="Wedding Photography" price="From $2,500">
    Service description here
</x-card>
--}}

@props([
    'variant' => 'default', // default, glass, flat, service, testimonial
    'hoverable' => false,
    'number' => null,
    'icon' => null,
    'title' => null,
    'price' => null,
    'badge' => null,
    'featured' => false,
])

@php
    // Base classes
    $baseClasses = 'relative';

    // Variant classes
    $variantClasses = match($variant) {
        'glass' => 'glass rounded-lg p-6',
        'flat' => 'bg-charcoal border border-iron',
        'service' => 'p-[44px_36px] bg-charcoal border border-iron cursor-default transition-colors overflow-hidden',
        'testimonial' => 'relative p-9 bg-charcoal border border-iron',
        default => 'bg-charcoal border border-iron rounded-lg p-6',
    };

    // Hover state
    $hoverClasses = '';
    if ($hoverable && $variant !== 'service') {
        $hoverClasses = 'hover:border-gold/30 hover:shadow-2xl hover:shadow-gold/5 transition-all';
    }

    $classes = trim("{$baseClasses} {$variantClasses} {$hoverClasses}");
@endphp

@if($variant === 'service')
    <div {{ $attributes->merge(['class' => $classes]) }}>
        {{-- Gold underline effect on hover --}}
        <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-gold to-transparent scale-x-0 group-hover:scale-x-100 transition-transform" style="transition-duration: var(--duration-slow)"></div>

        @if($number)
            <div class="font-serif text-[3.5rem] font-light text-gold/10 leading-none mb-4 transition-colors group-hover:text-gold/20" style="transition-duration: var(--duration-normal)">
                {{ $number }}
            </div>
        @endif

        @if($icon)
            <div class="text-[1.8rem] mb-4">
                <x-dynamic-component :component="$icon" />
            </div>
        @endif

        @if($title)
            <h3 class="font-serif text-[1.4rem] font-normal text-white mb-3">{{ $title }}</h3>
        @endif

        <div class="mb-5 text-[0.8rem] leading-[1.8] text-silver">
            {{ $slot }}
        </div>

        @if($price)
            <div class="font-serif text-[1.1rem] italic text-gold-lt">{{ $price }}</div>
        @endif
    </div>
@elseif($variant === 'testimonial')
    <div {{ $attributes->merge(['class' => $classes]) }}>
        {{-- Quote mark --}}
        <div class="absolute top-2.5 left-6 font-serif text-[6rem] text-gold/10 leading-none">"</div>

        {{ $slot }}

        @if(isset($author))
            {{ $author }}
        @endif
    </div>
@else
    <div {{ $attributes->merge(['class' => $classes]) }}>
        @if($badge)
            <div class="absolute top-[-1px] left-1/2 -translate-x-1/2 z-10 p-[5px_16px] bg-gold text-black text-[0.58rem] tracking-[0.25em] uppercase font-medium whitespace-nowrap">
                {{ $badge }}
            </div>
        @endif

        @if($title)
            <h3 class="font-serif text-xl font-normal text-white mb-4">{{ $title }}</h3>
        @endif

        {{ $slot }}
    </div>
@endif
