@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/input.blade.php -->
    <!-- {{ $__path }} -->
@endif

@props([
    'type' => 'text',
    'iconLeft' => null,
    'iconRight' => null,
])

<div class="relative w-full">
    {{-- Left Icon (optional) --}}
    @if ($iconLeft)
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-muted">
            {{ $iconLeft }}
        </span>
    @endif

    {{-- Input Field --}}
    <input type="{{ $type }}"
        {{ $attributes->merge([
            'class' =>
                'rounded px-3 py-2 bg-card text-default border border-default
                                         focus:ring-brand-500 focus:border-brand-500 w-full
                                         ' .
                ($iconLeft ? 'pl-10 ' : '') .
                ($iconRight ? 'pr-10 ' : ''),
        ]) }} />

    {{-- Right Icon (optional) --}}
    @if ($iconRight)
        <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-muted">
            {{ $iconRight }}
        </span>
    @endif
</div>
