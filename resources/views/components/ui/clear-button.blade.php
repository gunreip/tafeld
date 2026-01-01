@props(['icon' => 'x-mark', 'label' => 'Löschen'])

<button
    type="button"
    {{ $attributes->merge(['class' => 'ui-clear-button', 'aria-label' => $label, 'tabindex' => '-1']) }}
    x-cloak
> 
    @if(trim((string) $slot) !== '')
        {{ $slot }}
    @else
        <x-ui.icon :name="$icon" class="w-4 h-4" />
    @endif
</button>
