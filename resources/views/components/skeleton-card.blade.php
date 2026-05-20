{{--
<x-skeleton.card>
    <x-skeleton type="image" class="mb-4" />
    <x-skeleton type="title" class="mb-2" />
    <x-skeleton type="text" count="2" />
</x-skeleton.card>
--}}

@props([
    'padding' => true,
])

<div {{ $attributes->merge(['class' => 'bg-charcoal border border-iron rounded-lg overflow-hidden' . ($padding ? ' p-6' : '')]) }}>
    {{ $slot }}
</div>
