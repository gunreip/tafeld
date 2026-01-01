@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/accordion/item.blade.php -->
    <!-- {{ $__path }} -->
@endif

@props([
    'id',
    'title',
    'open' => false,
    'icon' => 'chevron-down', // erst nutzbar, wenn Icon-System fertig ist
])

@php
    // Für den initialen Zustand
    $initialOpen = $open ? 'true' : 'false';
@endphp

<div x-data="{ initOpen: {{ $initialOpen }} }" x-init="if (initOpen) {
    $root.closest('[x-data]').__x.$data.openItems['{{ $id }}'] = true;
}" class="border border-default rounded-lg bg-card">
    {{-- HEADER --}}
    <button type="button" @click="$root.closest('[x-data]').__x.$data.toggle('{{ $id }}')"
        class="w-full flex items-center justify-between px-4 py-3 text-default text-left
               hover:bg-hover rounded-lg transition">
        <span class="font-medium">{{ $title }}</span>

        {{-- Chevron (wird später durch echte Icons ersetzt) --}}
        <span class="transform transition-transform duration-200 text-muted"
            :class="$root.closest('[x-data]').__x.$data.isOpen('{{ $id }}') ? 'rotate-180' : ''">
            ▼
        </span>
    </button>

    {{-- CONTENT --}}
    <div x-show="$root.closest('[x-data]').__x.$data.isOpen('{{ $id }}')" x-collapse
        class="px-4 py-3 text-default bg-card border-t border-default">
        {{ $slot }}
    </div>
</div>
