{{--
<x-skeleton.list :items="3">
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
    'items' => 3,
    'gap' => 'gap-4',
])

<div {{ $attributes->merge(['class' => "flex flex-col {$gap}"]) }}>
    @foreach(range(1, $items) as $i)
        {{ $slot }}
    @endforeach
</div>
