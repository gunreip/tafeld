@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/alert.blade.php -->
    <!-- {{ $__path }} -->
@endif

@props([
    'type' => 'info', // success, warning, danger, info
])

@php
    $baseClasses = 'w-full rounded-lg px-4 py-3 flex items-start gap-3 border';

    $types = [
        'success' => [
            'bg' => 'bg-success-soft',
            'text' => 'text-success',
            'border' => 'border-success',
            'icon' => 'check-circle',
        ],
        'warning' => [
            'bg' => 'bg-warning-soft',
            'text' => 'text-warning',
            'border' => 'border-warning',
            'icon' => 'exclamation-triangle',
        ],
        'danger' => [
            'bg' => 'bg-danger-soft',
            'text' => 'text-danger',
            'border' => 'border-danger',
            'icon' => 'x-circle',
        ],
        'info' => [
            'bg' => 'bg-info-soft',
            'text' => 'text-info',
            'border' => 'border-info',
            'icon' => 'information-circle',
        ],
    ];

    $variant = $types[$type] ?? $types['info'];
@endphp

<div {{ $attributes->merge(['class' => "{$baseClasses} {$variant['bg']} {$variant['border']}"]) }}>
    <x-dynamic-component :component="'heroicon-o-' . $variant['icon']" class="w-5 h-5 {{ $variant['text'] }} flex-shrink-0" />

    <div class="text-sm {{ $variant['text'] }}">
        {{ $slot }}
    </div>
</div>
