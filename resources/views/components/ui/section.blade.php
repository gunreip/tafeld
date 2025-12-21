{{-- tafeld/resources/views/components/ui/section.blade.php --}}

@props([
    'title' => null,
    'subtitle' => null,
    'icon' => null, // optionales Heroicon, z. B. "user", "map", "cog-6-tooth"
    'border' => true, // Trennlinie unten
    'padding' => 'py-4', // py-4 | py-6 | py-2
])

<section {{ $attributes->merge(['class' => $padding . ' space-y-4']) }}>

    @if ($title || $subtitle)
        <header class="flex items-start gap-3">

            @if ($icon)
                <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-5 h-5 text-brand-500 shrink-0 mt-1" />
            @endif

            <div>
                @if ($title)
                    <h2 class="text-lg font-semibold text-default">{{ $title }}</h2>
                @endif

                @if ($subtitle)
                    <p class="text-sm text-muted">{{ $subtitle }}</p>
                @endif
            </div>

        </header>
    @endif

    <div class="space-y-4">
        {{ $slot }}
    </div>

    @if ($border)
        <div class="border-t border-default pt-4"></div>
    @endif

</section>
