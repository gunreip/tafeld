@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/drawer.blade.php -->
    <!-- {{ $__path }} -->
@endif

@props([
    'side' => 'right', // right | left
    'model' => null, // wire:model="isOpen" (optional)
    'width' => 'w-96', // Standardbreite, kann überschrieben werden
    'title' => null, // Optionaler Titel im Header
])

@php
    $sideClasses = [
        'right' => 'right-0 translate-x-full',
        'left' => 'left-0 -translate-x-full',
    ][$side];

    $enterFrom = $side === 'right' ? 'translate-x-full' : '-translate-x-full';
    $enterTo = 'translate-x-0';
@endphp

<div x-data="{ open: @js($model ? null : false) }" @if ($model) x-modelable="open" x-model="{{ $model }}" @endif
    class="relative z-[9998]">

    <!-- Overlay -->
    <div x-show="open" x-transition.opacity @click="open = false" class="fixed inset-0 bg-black/40 backdrop-blur-sm">
    </div>

    <!-- Drawer -->
    <div x-show="open" x-transition:enter="transition transform ease-out duration-300"
        x-transition:enter-start="{{ $enterFrom }} opacity-0" x-transition:enter-end="{{ $enterTo }} opacity-100"
        x-transition:leave="transition transform ease-in duration-200"
        x-transition:leave-start="{{ $enterTo }} opacity-100"
        x-transition:leave-end="{{ $enterFrom }} opacity-0"
        class="fixed top-0 bottom-0 {{ $side }} {{ $width }}
               bg-card text-default border-default border-l 
               shadow-xl flex flex-col"
        :class="{ 'hidden': !open }">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-default">
            <h2 class="text-lg font-semibold">
                {{ $title }}
            </h2>

            <button @click="open = false" class="text-muted hover:text-default text-xl leading-none">
                &times;
            </button>
        </div>

        <!-- Content -->
        <div class="flex-1 overflow-y-auto p-6">
            {{ $slot }}
        </div>

        <!-- Footer (optional) -->
        @if (isset($actions))
            <div class="px-6 py-4 border-t border-default bg-elevated text-right">
                {{ $actions }}
            </div>
        @endif
    </div>

</div>
