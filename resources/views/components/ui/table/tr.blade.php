@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/table/tr.blade.php -->
    <!-- {{ $__path }} -->
@endif

<tr {{ $attributes->merge([
    'class' => 'border-b border-default odd:bg-card even:bg-elevated hover:bg-card transition-colors'
]) }}>
    {{ $slot }}
</tr>
