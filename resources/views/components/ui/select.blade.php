{{-- tafeld/resources/views/components/ui/select.blade.php --}}

@props([
    'iconLeft' => null,
])

<div class="relative w-full">
    {{-- Left Icon --}}
    @if ($iconLeft)
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-muted pointer-events-none">
            {{ $iconLeft }}
        </span>
    @endif

    <select
        {{ $attributes->merge([
            'class' =>
                'rounded px-3 py-2 bg-card text-default border border-default
                                         focus:ring-brand-500 focus:border-brand-500 w-full
                                         appearance-none
                                         ' . ($iconLeft ? 'pl-10 ' : ''),
        ]) }}>
        {{ $slot }}
    </select>

    {{-- Caret Icon --}}
    <span class="pointer-events-none absolute inset-y-0 right-0 pr-3 flex items-center text-muted">
        {{-- Standard Heroicon --}}
        <x-heroicon-o-chevron-down class="w-4 h-4" />
    </span>
</div>
