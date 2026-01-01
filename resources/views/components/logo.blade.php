@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/logo.blade.php -->
    <!-- {{ $__path }} -->
@endif

@php
    // Default size presets (for raster images)
    $sizePresets = [
        'sm' => 'h-6',
        'md' => 'h-8',
        'lg' => 'h-10',
        'xl' => 'h-12',
    ];

    // Final CSS size (if width/height not given)
    $cssClass = $size ? $sizePresets[$size] ?? $sizePresets['md'] : '';

    // Use explicit width/height attributes (for SVG)
    $attrWidth = $width ? "width=\"{$width}\"" : '';
    $attrHeight = $height ? "height=\"{$height}\"" : '';

    // File lookup table
    $sources = [
        'icon' => [
            'light' => asset('images/tafeld/logos/icon-light.svg'),
            'dark' => asset('images/tafeld/logos/icon-dark.svg'),
        ],
        'full' => [
            'light' => asset('images/tafeld/logos/full-light.svg'),
            'dark' => asset('images/tafeld/logos/full-dark.svg'),
        ],
        'die-tafeln' => [
            'light' => asset('images/tafeld/logos/die-tafeln-light.svg'),
            'dark' => asset('images/tafeld/logos/die-tafeln-dark.svg'),
        ],
    ];

    $srcLight = $sources[$variant]['light'] ?? $sources['icon']['light'];
    $srcDark = $sources[$variant]['dark'] ?? $sources['icon']['dark'];

    // Determine file type (svg or raster)
    $isSvg = str_ends_with($srcLight, '.svg');
@endphp

<!-- tafeld/resources/views/components/logo.blade.php -->

{{-- SVG handling --}}
@if ($isSvg)
    <picture>
        <source srcset="{{ $srcDark }}" media="(prefers-color-scheme: dark)">
        <img src="{{ $srcLight }}" {!! $attrWidth !!} {!! $attrHeight !!}
            class="{{ $cssClass }} {{ $attributes->get('class') }}" alt="tafeld logo">
    </picture>

    {{-- Raster (PNG/JPG) --}}
@else
    <picture>
        <source srcset="{{ $srcDark }}" media="(prefers-color-scheme: dark)">
        <img src="{{ $srcLight }}" class="{{ $cssClass }} {{ $attributes->get('class') }}" alt="tafeld logo">
    </picture>
@endif
