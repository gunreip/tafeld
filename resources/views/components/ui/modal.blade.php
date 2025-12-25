@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/modal.blade.php -->
    <!-- {{ $__path }} -->
@endif


@props([
    'model' => null, // wire:model="isOpen"
    'maxWidth' => 'max-w-lg', // sm, md, lg, xl, full
    'title' => null, // Optionaler Header-Titel
])

@php
    $widthClass = match ($maxWidth) {
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        'full' => 'w-full max-w-full',
        default => $maxWidth, // custom Klassen möglich
    };
@endphp

<div x-data="{ open: @js($model ? null : false) }" @if ($model) x-modelable="open" x-model="{{ $model }}" @endif
    class="relative z-[9999]">

    <!-- Overlay -->
    <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black/40 backdrop-blur-sm"></div>

    <!-- Modal -->
    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" role="dialog"
        class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-card text-default rounded-lg border border-default shadow-xl w-full {{ $widthClass }}">

            <!-- Header -->
            @if ($title)
                <div class="px-6 py-4 border-b border-default flex justify-between items-center">
                    <h2 class="text-lg font-semibold">
                        {{ $title }}
                    </h2>

                    <button @click="open = false" class="text-muted hover:text-default text-xl leading-none">
                        &times;
                    </button>
                </div>
            @endif

            <!-- Body -->
            <div class="p-6">
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

</div>
