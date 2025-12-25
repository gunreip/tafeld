@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/icon.blade.php -->
    <!-- {{ $__path }} -->
@endif

@props([
    'name', // z.B. "user", "home", "trash"
    'variant' => 'outline', // "outline" | "solid"
    'size' => 'md', // "xs" | "sm" | "md" | "lg" | "xl"
    'wrapper' => false,
])

@php
    // Variante normalisieren
    $variant = $variant === 'solid' ? 'solid' : 'outline';

    // Größen-Mapping
    $sizeClasses = [
        'xs' => 'w-3 h-3',
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
        'xl' => 'w-7 h-7',
    ];

    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];

    // Heroicon-Alias bestimmen
    $prefix = $variant === 'solid' ? 'heroicon-s-' : 'heroicon-o-';
    $component = $prefix . $name;
@endphp

@if ($wrapper)
    <div class="flex items-center justify-center px-3 bg-elevated text-default">
        <x-dynamic-component :component="$component" {{ $attributes->merge(['class' => $sizeClass]) }} />
    </div>
@else
    <x-dynamic-component :component="$component" {{ $attributes->merge(['class' => $sizeClass]) }} />
@endif
