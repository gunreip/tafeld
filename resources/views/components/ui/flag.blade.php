{{-- tafeld/resources/views/components/ui/flag.blade.php --}}

{{-- tafeld/resources/views/components/ui/flag.blade.php --}}
@props([
    'code', // z.B. "de", "fr", "us", "ac"
    'label' => null, // optionaler Text neben der Flagge
    'size' => 'md', // xs | sm | md | lg | xl
    'inline' => false, // true = nur die Flagge, kein Wrapper
])

@php
    // Normalisiere Ländercode
    $code = strtolower($code);

    // Größenklassen der Flaggen
    $sizeMap = [
        'xs' => 'w-3 h-3',
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
        'xl' => 'w-8 h-8',
    ];

    $imgSize = $sizeMap[$size] ?? $sizeMap['md'];

    // Bildpfad
    $src = asset("flags/{$code}.svg");
@endphp

@if ($inline)
    {{-- Nur Flagge --}}
    <img src="{{ $src }}" alt="{{ strtoupper($code) }} Flagge"
        class="{{ $imgSize }} object-cover rounded-sm" />
@else
    {{-- Flagge + optionaler Text --}}
    <div class="flex items-center gap-2">
        <img src="{{ $src }}" alt="{{ strtoupper($code) }} Flagge"
            class="{{ $imgSize }} object-cover rounded-sm" />

        @if ($label)
            <span class="text-default">
                {{ $label }}
            </span>
        @endif
    </div>
@endif
