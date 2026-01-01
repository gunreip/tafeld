@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/textarea.blade.php -->
    <!-- {{ $__path }} -->
@endif

@props([
    'rows' => 4,
])

<textarea rows="{{ $rows }}"
    {{ $attributes->merge([
        'class' => 'w-full rounded px-3 py-2 bg-card text-default border border-default
                 focus:ring-brand-500 focus:border-brand-500 resize-y',
    ]) }}>
    {{ $slot }}
</textarea>
