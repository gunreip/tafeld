@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/card.blade.php -->
    <!-- {{ $__path }} -->
@endif

@props([
    'padding' => 'p-6', // p-4 | p-6 | p-8 etc.
    'shadow' => 'shadow-sm', // shadow-none | shadow-sm | shadow-md
    'rounded' => 'rounded-lg', // rounded-md | rounded-lg | rounded-xl
])

<div
    {{ $attributes->merge([
        'class' => "bg-card border border-default text-default {$rounded} {$shadow} {$padding}",
    ]) }}>
    {{ $slot }}
</div>
