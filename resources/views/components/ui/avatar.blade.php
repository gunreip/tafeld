{{-- tafeld/resources/views/components/ui/avatar.blade.php --}}

@props([
    'src' => null, // Bild-URL
    'name' => null, // Für Initialen
    'size' => 'md', // xs | sm | md | lg | xl
    'rounded' => 'full', // none | sm | md | lg | full
])

@php
    // Größen-Mapping
    $sizes = [
        'xs' => 'w-6 h-6 text-xs',
        'sm' => 'w-8 h-8 text-sm',
        'md' => 'w-10 h-10 text-base',
        'lg' => 'w-12 h-12 text-lg',
        'xl' => 'w-16 h-16 text-xl',
    ];

    // Rundungs-Mapping
    $roundedMap = [
        'none' => 'rounded-none',
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'full' => 'rounded-full',
    ];

    // Initialen generieren
    $initials = '';
    if ($name) {
        $parts = explode(' ', $name);
        $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
    }
@endphp

<div
    class="inline-flex items-center justify-center bg-elevated text-default font-semibold overflow-hidden
            {{ $sizes[$size] ?? $sizes['md'] }}
            {{ $roundedMap[$rounded] ?? $roundedMap['full'] }}
            border border-default select-none">

    {{-- Bild, falls vorhanden --}}
    @if ($src)
        <img src="{{ $src }}" alt="{{ $name ?? 'avatar' }}"
            class="object-cover w-full h-full {{ $roundedMap[$rounded] ?? $roundedMap['full'] }}">
    @else
        {{-- Initialen-Fallback --}}
        <span>{{ $initials ?: '?' }}</span>
    @endif
</div>
