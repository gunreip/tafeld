@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/input/debug-input.blade.php -->
    <!-- {{ $__path }} -->
@endif

<input
    type="text"
    {{ $attributes->merge([
        'class' => 'input-base rounded-md'
    ]) }}
>
