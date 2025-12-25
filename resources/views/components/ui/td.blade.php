@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/td.blade.php -->
    <!-- {{ $__path }} -->
@endif

{{-- table-td --}}

<td {{ $attributes->merge(['class' => 'px-4 py-2 text-default']) }}>
    {{ $slot }}
</td>
