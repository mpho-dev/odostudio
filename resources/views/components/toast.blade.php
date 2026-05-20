{{--
Add to your layout (before closing body tag):
<x-toast-container />

To show a toast from anywhere:
<x-button @click="$dispatch('toast', { type: 'success', title: 'Saved!', message: 'Your changes have been saved.' })">
    Save
</x-button>

Or from Alpine:
<button @click="$dispatch('toast', { type: 'error', title: 'Error', message: 'Something went wrong.' })">
    Trigger Error
</button>

Or from Livewire/Blade:
<x-toast type="success" title="Success" message="Item created successfully." />
--}}

@props([
    'type' => 'info', // info, success, error, warning
    'title' => null,
    'message' => null,
    'duration' => 5000,
    'id' => null,
])

@php
    $toastId = $id ?? 'toast-' . Str::random(8);
@endphp

<div
    x-data="{
        show: true,
        id: '{{ $toastId }}',
        type: '{{ $type }}',
        title: {{ json_encode($title) }},
        message: {{ json_encode($message) }}
    }"
    x-init="setTimeout(() => show = false, {{ $duration }})"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-full"
    x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 translate-x-full"
    class="toast toast-{{ $type }}"
>
    {{-- Icon --}}
    <div class="toast-icon">
        @if($type === 'success')
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        @elseif($type === 'error')
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        @elseif($type === 'warning')
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        @else
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        @endif
    </div>

    {{-- Content --}}
    <div class="toast-content">
        @if($title)
            <p class="toast-title">{{ $title }}</p>
        @endif
        @if($message)
            <p class="toast-message">{{ $message }}</p>
        @endif
    </div>

    {{-- Close button --}}
    <button @click="show = false" class="toast-close" aria-label="Close">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
