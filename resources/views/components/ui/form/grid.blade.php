{{-- tafeld/resources/views/components/ui/form-grid.blade.php --}}

@props([
    // Anzahl der Spalten im Default (z. B. 2 → zwei nebeneinander)
    'cols' => 2,

    // Responsive Breakpoints
    'sm' => null,
    'md' => null,
    'lg' => null,
    'xl' => null,

    // Abstand (gap) → default 4
    'gap' => '4',
])

@php
    // Grundklasse: grid + gap
    $classes = "grid gap-$gap grid-cols-$cols";

    // Responsive Spalten hinzufügen (nur wenn gesetzt)
    if ($sm) {
        $classes .= " sm:grid-cols-$sm";
    }
    if ($md) {
        $classes .= " md:grid-cols-$md";
    }
    if ($lg) {
        $classes .= " lg:grid-cols-$lg";
    }
    if ($xl) {
        $classes .= " xl:grid-cols-$xl";
    }
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
