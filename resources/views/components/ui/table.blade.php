@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/table.blade.php -->
    <!-- {{ $__path }} -->
@endif

{{-- table-table --}}

@props([
    'striped' => true,
    'hover' => true,
    'divider' => true,
])

@php
    $tableClass = 'w-full border-collapse text-default text-sm';

    if ($divider) {
        $tableClass .= ' divide-y divide-default';
    }
@endphp

<table {{ $attributes->merge(['class' => $tableClass]) }}>
    {{ $slot }}
</table>
