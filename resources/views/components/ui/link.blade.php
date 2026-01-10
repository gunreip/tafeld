@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/link.blade.php -->
    <!-- {{ $__path }} -->
@endif

@props([
    'href' => '#',
])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'text-brand-500 hover:underline text-sm']) }}>
    {{ $slot }}
</a>
