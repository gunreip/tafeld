@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/toggle.blade.php -->
    <!-- {{ $__path }} -->
@endif

@props([
    'model' => null, // Livewire: wire:model
    'checked' => false, // Default-Zustand
    'disabled' => false,
])

@php
    $base = 'relative inline-flex h-6 w-11 items-center rounded-full cursor-pointer transition-colors duration-200';
@endphp

<label class="flex items-center gap-3 select-none">

    {{-- Actual checkbox (hidden but accessible) --}}
    <input type="checkbox" @if ($model) wire:model="{{ $model }}" @endif
        @checked($checked) @disabled($disabled) class="peer sr-only">

    {{-- SLIDER --}}
    <span
        class="{{ $base }}
               peer-disabled:opacity-50
               peer-checked:bg-brand-500
               bg-border
        ">
        {{-- CIRCLE --}}
        <span
            class="pointer-events-none h-4 w-4 rounded-full bg-card shadow
                     transform transition-transform duration-200
                     peer-checked:translate-x-5 translate-x-1">
        </span>
    </span>

    {{-- OPTIONAL TEXT --}}
    @if (isset($slot) && trim($slot) !== '')
        <span class="text-default text-sm">
            {{ $slot }}
        </span>
    @endif

</label>
