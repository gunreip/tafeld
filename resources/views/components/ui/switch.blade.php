@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/switch.blade.php -->
    <!-- {{ $__path }} -->
@endif

@props([
    'model' => null, // wire:model Unterstützung
    'disabled' => false,
    'id' => uniqid('switch-'),
])

<label for="{{ $id }}" class="flex items-center gap-2 cursor-pointer select-none">

    {{-- Native Checkbox --}}
    <input type="checkbox" id="{{ $id }}"
        @if ($model) wire:model="{{ $model }}" @endif @disabled($disabled)
        class="peer sr-only">

    {{-- VISUAL SWITCH --}}
    <span
        class="relative inline-block w-9 h-5 rounded-full
                 transition-colors duration-150
                 bg-border peer-checked:bg-brand-500 peer-disabled:opacity-50">

        {{-- INNER DOT --}}
        <span
            class="absolute top-[2px] left-[2px] w-4 h-4 rounded-full bg-card shadow
                     transition-transform duration-150
                     peer-checked:translate-x-4">
        </span>
    </span>

    {{-- OPTIONAL LABEL --}}
    @if (trim($slot) !== '')
        <span class="text-default text-sm">{{ $slot }}</span>
    @endif

</label>
