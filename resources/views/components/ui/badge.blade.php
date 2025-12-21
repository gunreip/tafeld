{{-- tafeld/resources/views/components/ui/badge.blade.php --}}

@props([
    'color' => 'default', // default | brand | success | warning | danger | info
    'variant' => 'solid', // solid | soft | outline
    'rounded' => 'md', // none | sm | md | lg | full
])

@php
    // Farb-Mapping (Theme-Variablen)
    $colors = [
        'default' => [
            'solid' => 'bg-default text-inverted',
            'soft' => 'bg-hover text-default',
            'outline' => 'border border-default text-default',
        ],
        'brand' => [
            'solid' => 'bg-brand-500 text-inverted',
            'soft' => 'bg-brand-soft text-brand-500',
            'outline' => 'border border-brand-500 text-brand-500',
        ],
        'success' => [
            'solid' => 'bg-success text-inverted',
            'soft' => 'bg-success-soft text-success',
            'outline' => 'border border-success text-success',
        ],
        'warning' => [
            'solid' => 'bg-warning text-inverted',
            'soft' => 'bg-warning-soft text-warning',
            'outline' => 'border border-warning text-warning',
        ],
        'danger' => [
            'solid' => 'bg-danger text-inverted',
            'soft' => 'bg-danger-soft text-danger',
            'outline' => 'border border-danger text-danger',
        ],
        'info' => [
            'solid' => 'bg-info text-inverted',
            'soft' => 'bg-info-soft text-info',
            'outline' => 'border border-info text-info',
        ],
    ];

    $roundedMap = [
        'none' => 'rounded-none',
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'full' => 'rounded-full',
    ];
@endphp

<span
    {{ $attributes->merge([
        'class' =>
            'inline-flex items-center px-2.5 py-0.5 text-xs font-medium select-none ' .
            ($colors[$color][$variant] ?? $colors['default']['solid']) .
            ' ' .
            ($roundedMap[$rounded] ?? $roundedMap['md']),
    ]) }}>
    {{ $slot }}
</span>
