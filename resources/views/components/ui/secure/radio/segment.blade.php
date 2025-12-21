{{-- tafeld/resources/views/components/ui/radio/segment.blade.php --}}

@props([
    'options' => [],   // ['value' => 'Label', ...]
    'disabled' => false,
])

@php
    // Livewire-Binding (z. B. wire:model="salutation")
    $wireModel = $attributes->wire('model');
@endphp

<div
    {{ $attributes
        ->except('wire:model')
        ->class('radio-segment-wrapper input-group-divider') }}
>
    @foreach ($options as $optionValue => $optionLabel)
        <label class="flex-1">
            <input
                type="radio"
                value="{{ $optionValue }}"
                {{ $wireModel }}
                @disabled($disabled)
                class="peer sr-only"
            />

            <div class="radio-segment">
                <span>{{ $optionLabel }}</span>
                <span class="radio-segment-check">
                    <x-ui.icon name="check" size="lg" />
                </span>
            </div>
        </label>
    @endforeach
</div>
