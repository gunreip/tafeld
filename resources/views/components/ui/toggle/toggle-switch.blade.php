@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/toggle/toggle-switch.blade.php -->
    <!-- {{ $__path }} -->
@endif

@props([
    'disabled' => false,
])

@php
    // Livewire-Binding (z. B. wire:model="work_permit")
    $wireModel = $attributes->wire('model');
@endphp

<label class="inline-flex items-center cursor-pointer select-none">

    <input
        type="checkbox"
        {{ $wireModel }}
        @disabled($disabled)
        class="sr-only peer"
    />

    <div class="toggle-switch">

        {{-- Toggle-Knopf --}}
        <div class="toggle-switch-knob"></div>

        {{-- Icon: Checked (✓) --}}
        <div class="w-24 toggle-switch-icon toggle-switch-icon-check">
            {{-- ✓ --}}
            <x-ui.icon name="check" class="text-green-900" size="lg" />
        </div>

        {{-- Icon: Unchecked (✕) --}}
        <div class="w-24 toggle-switch-icon toggle-switch-icon-cross">
            {{-- ✕ --}}
            <x-ui.icon name="x-mark" size="lg" />
        </div>

    </div>

</label>
