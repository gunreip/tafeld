@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/tabs.blade.php -->
    <!-- {{ $__path }} -->
@endif

@props([
    'tabs' => [], // ['id' => 'label', ...]
    'default' => null, // Default aktiver Tab
    'model' => null, // Livewire: wire:model="activeTab"
    'orientation' => 'horizontal', // horizontal|vertical
])

@php
    $isVertical = $orientation === 'vertical';
@endphp

<div x-data="{
    active: @js($default ?? (count($tabs) ? array_key_first($tabs) : null)),
}" @if ($model) x-modelable="active" x-model="{{ $model }}" @endif
    class="w-full">

    {{-- TAB-LIST --}}
    <div @class([
        'flex border-b border-default',
        'flex-col border-b-0 border-r' => $isVertical,
    ])>

        @foreach ($tabs as $id => $label)
            <button @click="active = '{{ $id }}'" type="button" @class([
                'px-4 py-2 text-sm font-medium transition-colors',
                'text-muted hover:text-default hover:bg-hover',
                'border-default',
                // horizontal active style
                'border-b-2' => !$isVertical,
                // vertical active style
                'border-r-2' => $isVertical,
            ])
                :class="active === '{{ $id }}'
                    ?
                    'text-default border-brand-500' :
                    'border-transparent'">
                {{ $label }}
            </button>
        @endforeach

    </div>

    {{-- PANELS --}}
    <div class="mt-4">
        {{ $slot }}
    </div>

</div>
