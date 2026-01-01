@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/table/td.blade.php -->
    <!-- {{ $__path }} -->
@endif

@props([
    'align' => 'left', // left | center | right
])

<td {{ $attributes->merge([
    'class' => match ($align) {
        'center' => 'px-4 py-2 text-center text-default',
        'right'  => 'px-4 py-2 text-right text-default',
        default  => 'px-4 py-2 text-left text-default',
    }
]) }}>
    {{ $slot }}
</td>
