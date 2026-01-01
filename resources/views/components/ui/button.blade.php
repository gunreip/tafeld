@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/button.blade.php -->
    <!-- {{ $__path }} -->
@endif

@props([
    'variant' => 'primary',
    'iconLeft' => null,
    'iconRight' => null,
    'type' => 'button',
])

@php
    $base = "inline-flex items-center justify-center gap-2
             font-medium rounded-md px-4 py-2
             transition focus:outline-none focus:ring-2 focus:ring-brand-500";

    $variants = [
        'primary' => 'btn-brand text-inverted',
        'secondary' => 'bg-elevated text-default border border-default hover:bg-hover',
        'danger' => 'bg-danger text-inverted hover:bg-danger-soft',
        'subtle' => 'text-default hover:bg-hover',
    ];

    $classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>

    {{-- Left Icon --}}
    @if ($iconLeft)
        <span class="flex items-center text-inverted/80">
            {{ $iconLeft }}
        </span>
    @endif

    {{-- Label --}}
    <span>{{ $slot }}</span>

    {{-- Right Icon --}}
    @if ($iconRight)
        <span class="flex items-center text-inverted/80">
            {{ $iconRight }}
        </span>
    @endif

</button>
