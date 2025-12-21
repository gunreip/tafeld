{{-- tafeld/resources/views/components/ui/form-row.blade.php --}}

@props([
    'columns' => 2, // 2 | 3 | 4 – Anzahl der Spalten
    'gap' => 'gap-4', // Abstände: gap-2 | gap-4 | gap-6
    'responsive' => true, // true = mobile stacked, desktop Grid
])

@php
    $base = $responsive ? "grid grid-cols-1 sm:grid-cols-{$columns}" : "grid grid-cols-{$columns}";

    $classes = "{$base} {$gap}";
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
