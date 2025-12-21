{{-- tafeld/resources/views/components/ui/table.blade.php --}}
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
