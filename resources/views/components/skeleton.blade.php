{{--
Basic skeletons:

<x-skeleton type="text" />
<x-skeleton type="title" />
<x-skeleton type="avatar" />
<x-skeleton type="card" />
<x-skeleton type="image" />

Skeleton patterns for common layouts:

<x-skeleton.card>
    <x-skeleton type="image" class="mb-4" />
    <x-skeleton type="title" class="mb-2" />
    <x-skeleton type="text" class="mb-2" />
    <x-skeleton type="text-sm" />
</x-skeleton.card>

<x-skeleton.list :items="5">
    <div class="flex items-center gap-4">
        <x-skeleton type="avatar" />
        <div class="flex-1">
            <x-skeleton type="title" class="mb-2" />
            <x-skeleton type="text-sm" />
        </div>
    </div>
</x-skeleton.list>
--}}

@props([
    'type' => 'text', // text, text-sm, title, avatar, card, image
    'count' => 1,
    'as' => 'div',
])

@php
    $tag = $as;
    $baseClass = 'skeleton';

    $typeClass = match($type) {
        'text' => 'skeleton-text',
        'text-sm' => 'skeleton-text-sm',
        'title' => 'skeleton-title',
        'avatar' => 'skeleton-avatar',
        'card' => 'skeleton-card',
        'image' => 'skeleton-image',
        default => 'skeleton-text',
    };
@endphp

@if($count > 1)
    @foreach(range(1, $count) as $i)
        <{{ $tag }} {{ $attributes->merge(['class' => "{$baseClass} {$typeClass}"]) }} aria-hidden="true"></{{ $tag }}>
    @endforeach
@else
    <{{ $tag }} {{ $attributes->merge(['class' => "{$baseClass} {$typeClass}"]) }} aria-hidden="true"></{{ $tag }}>
@endif
