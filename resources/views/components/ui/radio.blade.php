@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/radio.blade.php -->
    <!-- {{ $__path }} -->
@endif

@props([
    'model' => null, // wire:model Unterstützung
    'value' => null, // Wert dieses Radio-Buttons
    'id' => uniqid('radio-'),
    'disabled' => false,
])

<label for="{{ $id }}" class="flex items-center gap-3 cursor-pointer select-none">

    {{-- Hidden native radio --}}
    <input type="radio" id="{{ $id }}" value="{{ $value }}"
        @if ($model) wire:model="{{ $model }}" @endif @disabled($disabled)
        class="peer sr-only">

    {{-- VISUAL CIRCLE --}}
    <span
        class="relative w-5 h-5 rounded-full border border-default bg-card
                 peer-checked:border-brand-500 peer-checked:bg-brand-500/20
                 peer-disabled:opacity-50 transition-colors">

        {{-- Inner Dot --}}
        <span class="absolute inset-0 flex items-center justify-center">
            <span
                class="w-2.5 h-2.5 rounded-full bg-brand-500
                         opacity-0 peer-checked:opacity-100 transition-opacity"></span>
        </span>
    </span>

    {{-- Optional Label --}}
    @if (trim($slot) !== '')
        <span class="text-default text-sm">
            {{ $slot }}
        </span>
    @endif

</label>
