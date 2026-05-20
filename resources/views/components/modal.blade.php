{{--
Alpine-powered modal (recommended):

<x-modal x-ref="myModal" title="Delete Item" type="danger">
    <x-slot:trigger>
        <x-btn variant="danger" @click="$refs.myModal.open()">Delete</x-btn>
    </x-slot:trigger>

    Are you sure you want to delete this item? This action cannot be undone.

    <x-slot:footer>
        <form action="/delete" method="POST">
            @csrf
            @method('DELETE')
            <x-btn variant="danger" type="submit">Confirm Delete</x-btn>
        </form>
    </x-slot:footer>
</x-modal>

Alert modal (JavaScript controlled):

<x-modal.alert id="success-modal" title="Success" message="Item saved successfully!" type="success" />

Confirmation modal with form:

<x-modal.confirm
    x-ref="confirmModal"
    title="Delete User"
    message="This will permanently delete the user and all associated data."
    confirm-label="Delete User"
    action="/users/1"
    method="DELETE"
    variant="danger"
/>
--}}

@props([
    'id' => null,
    'title' => 'Notice',
    'message' => null,
    'type' => 'info', // info, success, error, danger, warning
    'confirmLabel' => 'Confirm',
    'action' => null,
    'method' => 'POST',
    'variant' => null, // null (uses type), danger, primary
    'closable' => true,
])

@php
    $modalId = $id ?? 'modal-' . Str::random(8);
    $effectiveVariant = $variant ?? ($type === 'error' ? 'danger' : ($type === 'danger' ? 'danger' : 'primary'));

    // Type to color mapping
    $colors = match($type) {
        'error', 'danger' => [
            'label' => 'text-red-400',
            'line' => 'bg-red-400/30',
            'icon' => 'text-red-400',
        ],
        'success' => [
            'label' => 'text-green-400',
            'line' => 'bg-green-400/30',
            'icon' => 'text-green-400',
        ],
        'warning' => [
            'label' => 'text-yellow-400',
            'line' => 'bg-yellow-400/30',
            'icon' => 'text-yellow-400',
        ],
        default => [
            'label' => 'text-gold',
            'line' => 'bg-gold/30',
            'icon' => 'text-gold',
        ],
    };

    $labelText = match($type) {
        'error' => 'Error',
        'danger' => 'Warning',
        'success' => 'Success',
        'warning' => 'Attention',
        default => 'Notice',
    };
@endphp

<div x-data="{ {{ $modalId }}Open: false }" {{ $attributes->only(['x-ref']) }}>
    {{-- Trigger slot --}}
    @if(isset($trigger))
        <div @click="{{ $modalId }}Open = true">
            {{ $trigger }}
        </div>
    @endif

    {{-- Modal backdrop and content --}}
    <div
        x-show="{{ $modalId }}Open"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50"
        role="dialog"
        aria-modal="true"
    >
        {{-- Backdrop --}}
        <div
            class="absolute inset-0 bg-black/80 backdrop-blur-sm"
            @if($closable) @click="{{ $modalId }}Open = false" @endif
        ></div>

        {{-- Modal content --}}
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-4">
            <div
                class="glass border border-white/10 rounded-xl p-8 shadow-2xl"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
            >
                {{-- Header --}}
                <div class="flex items-center gap-4 text-[0.62rem] tracking-[0.4em] uppercase {{ $colors['label'] }} mb-4">
                    <span>{{ $labelText }}</span>
                    <div class="h-px w-16 {{ $colors['line'] }}"></div>
                </div>

                {{-- Title --}}
                <h2 class="font-serif text-2xl text-charcoal dark:text-white mb-3">{{ $title }}</h2>

                {{-- Message --}}
                <p class="text-sm text-ash/70 dark:text-ash/60 mb-8 leading-relaxed">
                    {{ $message ?? $slot }}
                </p>

                {{-- Footer --}}
                <div class="flex justify-end gap-4">
                    @if($closable)
                        <button
                            type="button"
                            @click="{{ $modalId }}Open = false"
                            class="px-6 py-3 text-[0.6rem] tracking-[0.3em] uppercase font-bold text-ash/60 hover:text-charcoal dark:hover:text-white transition-colors"
                        >
                            Cancel
                        </button>
                    @endif

                    @if(isset($footer))
                        {{ $footer }}
                    @elseif($action)
                        <form action="{{ $action }}" method="POST">
                            @csrf
                            @method($method)
                            <x-btn variant="{{ $effectiveVariant === 'danger' ? 'danger' : 'primary' }}" type="submit">
                                {{ $confirmLabel }}
                            </x-btn>
                        </form>
                    @else
                        <x-btn variant="{{ $effectiveVariant === 'danger' ? 'danger' : 'primary' }}" @click="{{ $modalId }}Open = false">
                            {{ $closable ? 'OK' : 'Dismiss' }}
                        </x-btn>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script to expose open/close methods when using x-ref --}}
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('{{ $modalId }}', () => ({
            open() { this.{{ $modalId }}Open = true },
            close() { this.{{ $modalId }}Open = false },
            toggle() { this.{{ $modalId }}Open = !this.{{ $modalId }}Open },
        }));
    });
</script>
