@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/checkbox.blade.php -->
    <!-- {{ $__path }} -->
@endif

@props([
    'model' => null, // wire:model Unterstützung
    'checked' => false, // Default-Zustand
    'disabled' => false,
    'id' => uniqid('checkbox-'),
])

<label for="{{ $id }}" class="flex items-center gap-3 cursor-pointer select-none">

    {{-- Hidden checkbox --}}
    <input type="checkbox" id="{{ $id }}"
        @if ($model) wire:model="{{ $model }}" @endif @checked($checked)
        @disabled($disabled) class="peer sr-only">

    {{-- VISUAL BOX --}}
    <span
        class="flex items-center justify-center w-5 h-5 rounded border border-default bg-card 
                 transition-colors duration-200
                 peer-checked:bg-brand-500 peer-checked:border-brand-500
                 peer-disabled:opacity-50">

        {{-- CHECKMARK ICON --}}
        <svg class="w-4 h-4 text-inverted opacity-0 peer-checked:opacity-100 transition-opacity" fill="none"
            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M5 13l4 4L19 7" />
        </svg>
    </span>

    {{-- OPTIONALES LABEL --}}
    @if (trim($slot) !== '')
        <span class="text-default text-sm">
            {{ $slot }}
        </span>
    @endif

</label>
