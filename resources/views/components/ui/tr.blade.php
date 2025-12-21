{{-- tafeld/resources/views/components/ui/tr.blade.php --}}
{{-- table-tr --}}

@props([
    'hover' => true,
    'striped' => true,
])

@php
    $classes = 'border-b border-default';

    if ($striped) {
        $classes .= ' odd:bg-card even:bg-elevated';
    }
    if ($hover) {
        $classes .= ' hover:bg-hover transition-colors';
    }
@endphp

<tr {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</tr>
