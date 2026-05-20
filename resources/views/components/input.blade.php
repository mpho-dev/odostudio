{{--
<x-input label="Email" type="email" name="email" />

<x-input label="Password" type="password" name="password" hint="Must be at least 8 characters" />

<x-input label="Bio" type="textarea" name="bio" :error="$errors->first('bio')" />
--}}

@props([
    'label' => null,
    'name' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'hint' => null,
    'error' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'autocomplete' => null,
])

@php
    $inputId = $name ? ($attributes->get('id') ?? "input-{$name}") : ($attributes->get('id') ?? Str::random(8));
    $hasError = !empty($error);

    $baseClasses = 'w-full bg-charcoal/5 dark:bg-black/50 border p-3.5 text-sm text-charcoal dark:text-white outline-none transition-all';
    $borderClasses = $hasError
        ? 'border-red-500/50 focus:border-red-500 focus:ring-2 focus:ring-red-500/20'
        : 'border-charcoal/10 dark:border-white/10 focus:border-gold focus:ring-2 focus:ring-gold/20';

    $inputClasses = trim("{$baseClasses} {$borderClasses}");

    $labelClasses = 'block text-[0.62rem] tracking-[0.3em] uppercase text-ash/60 dark:text-silver mb-2';
    $hintClasses = 'mt-1.5 text-xs text-silver';
    $errorClasses = 'mt-1.5 text-xs text-red-400';
@endphp

<div class="mb-5">
    @if($label)
        <label for="{{ $inputId }}" class="{{ $labelClasses }}">
            {{ $label }}
            @if($required)
                <span class="text-gold">*</span>
            @endif
        </label>
    @endif

    @if($type === 'textarea')
        <textarea
            id="{{ $inputId }}"
            name="{{ $name }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $attributes->merge(['class' => $inputClasses]) }}
        >{{ old($name, $value) }}</textarea>
    @elseif($type === 'select')
        <select
            id="{{ $inputId }}"
            name="{{ $name }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge(['class' => $inputClasses]) }}
        >
            {{ $slot }}
        </select>
    @elseif($type === 'checkbox' || $type === 'radio')
        <div class="flex items-center gap-3">
            <input
                type="{{ $type }}"
                id="{{ $inputId }}"
                name="{{ $name }}"
                value="{{ $value }}"
                {{ $required ? 'required' : '' }}
                {{ $disabled ? 'disabled' : '' }}
                {{ old($name) == $value ? 'checked' : '' }}
                {{ $attributes->merge(['class' => 'w-4 h-4 rounded border-charcoal/10 dark:border-white/10 text-gold focus:ring-gold focus:ring-offset-black bg-charcoal/5 dark:bg-black/50']) }}
            />
            @if($label)
                <label for="{{ $inputId }}" class="text-sm text-ash">
                    {{ $label }}
                    @if($required)
                        <span class="text-gold">*</span>
                    @endif
                </label>
            @endif
        </div>
    @else
        <input
            type="{{ $type }}"
            id="{{ $inputId }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $autocomplete ? "autocomplete=\"{$autocomplete}\"" : '' }}
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $attributes->merge(['class' => $inputClasses]) }}
        />
    @endif

    @if($hasError)
        <p class="{{ $errorClasses }}" x-data x-init="$nextTick(() => $el.closest('div').querySelector('input, textarea, select')?.classList.add('input-error'))">
            {{ $error }}
        </p>
    @elseif($hint)
        <p class="{{ $hintClasses }}">{{ $hint }}</p>
    @endif
</div>
