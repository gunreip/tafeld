@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/select/debug-custom-select.blade.php -->
    <!-- {{ $__path }} -->
@endif

<div
    class="debug-custom-select"
    data-wire-model="{{ $attributes->wire('model') instanceof \Livewire\WireDirective ? $attributes->wire('model')->value() : $attributes->wire('model') }}"
    @keydown.arrow-down.prevent="open && next()"
    @keydown.arrow-up.prevent="open && prev()"
    @keydown.enter.prevent="handleEnter()"
    @keydown.space.prevent="open ? closeDropdown() : openDropdown()"
    @keydown.escape.prevent="closeDropdown()"
    @focusout="if (! $el.contains($event.relatedTarget)) closeDropdown()"
    x-data="debugCustomSelect({
        value: @entangle($attributes->wire('model')),
        options: @js($options)
    })"
    x-init="init()"
>

    <!-- Trigger -->
    <button
        class="debug-custom-select-trigger"
        type="button"
        @click="open ? closeDropdown() : openDropdown()"
    >
        <span
            class="debug-custom-select-trigger-inner"
            :class="currentOption()?.class ?? ''"
        >
            <span class="debug-custom-select-trigger-icon">
                @php
                    $triggerIcons = collect($options)
                        ->pluck('icon-name')
                        ->filter()
                        ->unique()
                        ->values();
                @endphp

                @foreach ($triggerIcons as $iconName)
                    <span x-show="currentIcon() === @js($iconName)">
                        <x-ui.icon :name="$iconName" />
                    </span>
                @endforeach
            </span>

            <span
                class="debug-custom-select-trigger-label"
                x-text="
                    currentOption()?.label
                    ?? options[activeIndex]?.label
                    ?? 'Bitte wählen'
                "
            ></span>
        </span>
    </button>

    <!-- Clear button (visible when a non-first option is selected) -->
    <x-ui.clear-button
        x-show="selectedIndex() > 0"
        @click.stop="clear()"
        aria-label="Clear selection"
    />

    <!-- Dropdown -->
    <div
        class="debug-custom-select-panel"
        x-show="open"
        x-cloak
        @click.outside="closeDropdown()"
    >
        <ul role="listbox">
            @foreach ($options as $index => $opt)
                <x-ui.select.options.select-option
                    :index="$index"
                    :value="$opt['value']"
                    :icon="$opt['icon-name'] ?? null"
                    :class="$opt['class'] ?? ''"
                >
                    {{ $opt['label'] }}
                </x-ui.select.options.select-option>
            @endforeach
        </ul>
    </div>

</div>
