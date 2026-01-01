@props(['label' => '✕'])

<button
    type="button"
    {{ $attributes->merge(['class' => 'ui-clear-button']) }}
    x-cloak
>
    {{ $slot ?? $label }}
</button>
