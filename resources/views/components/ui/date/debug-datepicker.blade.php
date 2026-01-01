@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/date/debug-datepicker.blade.php -->
    <!-- {{ $__path }} -->
@endif

<input
    type="date"
    {{ $attributes->merge([
        'class' => 'input-base rounded-md'
    ]) }}
>
