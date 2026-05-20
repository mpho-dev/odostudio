{{--
<x-btn>
    Button text
</x-btn>

<x-btn variant="secondary">
    Secondary button
</x-btn>

<x-btn variant="danger" size="lg">
    Large danger button
</x-btn>

<x-btn variant="ghost" href="/link">
    Link-style button
</x-btn>
--}}

@props([
    'variant' => 'primary', // primary, secondary, danger, ghost
    'size' => 'md', // sm, md, lg
    'type' => 'button',
    'href' => null,
    'disabled' => false,
    'loading' => false,
    'icon' => null,
])

@php
    // Base styles
    $baseClasses = 'inline-flex items-center justify-center font-sans font-bold uppercase tracking-[0.3em] transition-all overflow-hidden';

    // Size variants
    $sizeClasses = match($size) {
        'sm' => 'px-5 py-2 text-[0.55rem]',
        'lg' => 'px-10 py-4 text-[0.7rem]',
        default => 'px-8 py-3 text-[0.6rem]',
    };

    // Color variants
    $variantClasses = match($variant) {
        'primary' => 'relative bg-gold text-black hover:bg-white',
        'secondary' => 'border border-charcoal/10 dark:border-white/10 text-ash hover:border-gold hover:text-gold',
        'danger' => 'relative bg-red-500/10 border border-red-500/30 text-red-400 hover:bg-red-500 hover:text-white',
        'ghost' => 'text-ash hover:text-gold',
        default => 'relative bg-gold text-black hover:bg-white',
    };

    // Disabled state
    $disabledClasses = $disabled ? 'opacity-50 cursor-not-allowed' : '';

    // Duration based on variant
    $duration = match($variant) {
        'primary', 'danger' => 'duration-500',
        default => 'duration-300',
    };

    $classes = trim("{$baseClasses} {$sizeClasses} {$variantClasses} {$duration} {$disabledClasses}");
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }} @if($disabled) aria-disabled="true" @endif>
        @if($loading)
            <svg class="animate-spin -ml-1 mr-2 h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @elseif($icon)
            <x-dynamic-component :component="$icon" class="w-3.5 h-3.5" />
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }} @if($disabled || $loading) disabled @endif>
        @if($variant === 'primary')
            <span class="absolute inset-0 -translate-x-full group-hover:translate-x-0 transition-transform duration-500 bg-white/20"></span>
        @endif
        @if($variant === 'danger')
            <span class="absolute inset-0 -translate-x-full group-hover:translate-x-0 transition-transform duration-500 bg-white/10"></span>
        @endif
        @if($loading)
            <svg class="animate-spin -ml-1 mr-2 h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @elseif($icon)
            <x-dynamic-component :component="$icon" class="w-3.5 h-3.5 mr-2" />
        @endif
        {{ $slot }}
    </button>
@endif
