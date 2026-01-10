@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/select/debug-custom-select.blade.php -->
    <!-- {{ $__path }} -->
@endif

<div
    {{-- class="debug-custom-select" --}}
    class="debug-custom-select-wrapper"
    data-wire-model="{{ $attributes->wire('model') instanceof \Livewire\WireDirective ? $attributes->wire('model')->value() : $attributes->wire('model') }}"
    x-data="debugCustomSelect({
        value: @entangle($attributes->wire('model')),
        options: @js($options)
    })"
    @custom-select:set="
        value = $event.detail;
        closeDropdown();
    "
>
    <!-- Trigger -->
    <button
        class="debug-custom-select-trigger"
        type="button"
        @click="open ? closeDropdown() : openDropdown()"
    >
        <span
            class="debug-custom-select-trigger-inner"
            :class="displayedOption()?.['class'] ?? ''"
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
                    displayedOption()?.label
                    ?? 'Level wählen ...'
                "
            ></span>
        </span>
    </button>

    <!-- Clear button -->
    <x-ui.clear-button
        aria-label="Clear selection"
        @click.stop="clear()"
    />

    <!-- Dropdown -->
    <div
        class="debug-custom-select-panel"
        x-show="open"
        x-cloak
        @click.outside="closeDropdown()"
    >
        <ul
            role="listbox"
            @mouseover="previewFromEvent($event)"
            @mouseleave="clearPreview()"
        >
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
