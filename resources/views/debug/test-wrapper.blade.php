<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Statically include built assets for E2E test pages so they always load in CI/local regardless of Vite dev server state --}}
    @php
        $manifestPath = public_path('build/manifest.json');
        $viteManifest = file_exists($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
        $jsEntry = isset($viteManifest['resources/js/app.js']['file']) ? $viteManifest['resources/js/app.js']['file'] : null;
        $cssEntry = isset($viteManifest['resources/css/app.css']['file']) ? $viteManifest['resources/css/app.css']['file'] : null;
    @endphp

    @if ($cssEntry)
        <link rel="preload" as="style" href="{{ asset('build/' . $cssEntry) }}" />
        <link rel="stylesheet" href="{{ asset('build/' . $cssEntry) }}" />
    @endif

    <!-- DEBUG: manifestPath={{ $manifestPath }}; exists={{ file_exists($manifestPath) ? '1' : '0' }}; jsEntry={{ $jsEntry }}; cssEntry={{ $cssEntry }} -->

    @if ($jsEntry)
        <link rel="modulepreload" href="{{ asset('build/' . $jsEntry) }}" />
        <script type="module" src="{{ asset('build/' . $jsEntry) }}"></script>
    @endif

    <title>tafeld - debug test</title>
    @livewireStyles
</head>
<body>
    {!! $slot !!}

    @livewireScripts
</body>
</html>
