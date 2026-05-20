{{--
<x-badge>Default</x-badge>

<x-badge variant="gold">Featured</x-badge>

<x-badge variant="success" pulse>Active</x-badge>

<x-badge variant="danger">Cancelled</x-badge>

<x-badge variant="warning">Pending</x-badge>
--}}

@props([
    'variant' => 'default', // default, gold, success, danger, warning, info
    'pulse' => false,
    'size' => 'sm', // xs, sm, md
])

@php
    $baseClasses = 'inline-flex items-center gap-1.5 font-sans uppercase font-medium whitespace-nowrap';

    $sizeClasses = match($size) {
        'xs' => 'px-2 py-0.5 text-[0.5rem] tracking-[0.2em]',
        'md' => 'px-3 py-1 text-[0.6rem] tracking-[0.15em]',
        default => 'px-2.5 py-0.5 text-[0.55rem] tracking-[0.15em]',
    };

    $variantClasses = match($variant) {
        'gold' => 'bg-gold/10 text-gold border border-gold/30',
        'success' => 'bg-green-500/10 text-green-400 border border-green-500/30',
        'danger' => 'bg-red-500/10 text-red-400 border border-red-500/30',
        'warning' => 'bg-yellow-500/10 text-yellow-400 border border-yellow-500/30',
        'info' => 'bg-blue-500/10 text-blue-400 border border-blue-500/30',
        default => 'bg-iron/50 text-silver border border-white/10',
    };

    $classes = trim("{$baseClasses} {$sizeClasses} {$variantClasses}");
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($pulse)
        <span class="relative flex h-1.5 w-1.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75
                @if($variant === 'gold') bg-gold
                @elseif($variant === 'success') bg-green-400
                @elseif($variant === 'danger') bg-red-400
                @elseif($variant === 'warning') bg-yellow-400
                @elseif($variant === 'info') bg-blue-400
                @else bg-silver
                @endif
            "></span>
            <span class="relative inline-flex rounded-full h-1.5 w-1.5
                @if($variant === 'gold') bg-gold
                @elseif($variant === 'success') bg-green-400
                @elseif($variant === 'danger') bg-red-400
                @elseif($variant === 'warning') bg-yellow-400
                @elseif($variant === 'info') bg-blue-400
                @else bg-silver
                @endif
            "></span>
        </span>
    @endif
    {{ $slot }}
</span>
