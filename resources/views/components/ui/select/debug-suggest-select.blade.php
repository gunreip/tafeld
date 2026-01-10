@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/select/debug-suggest-select.blade.php -->
    <!-- {{ $__path }} -->
@endif

@props([
    'options',
    'mode' => 'hierarchical',
])

<div
    class="debug-suggest-select-wrapper"
    data-wire-model="{{ $attributes->wire('model') instanceof \Livewire\WireDirective
        ? $attributes->wire('model')->value()
        : $attributes->wire('model') }}"
    x-data="debugSuggestInput({
        value: @entangle($attributes->wire('model')),
        options: @js($options),
        mode: '{{ $mode ?? 'hierarchical' }}'
    })"
    @click.outside="close()"
>
    <!-- Input -->
    <input
        type="text"
        class="input-base input-base-border debug-suggest-select-field"
        placeholder="{{ $placeholder ?? 'Scope …' }}"
        {{ $attributes->wire('model') }}
        @input="onInput($event)"
        @click="open = !open"
        @keydown.arrow-down.prevent="next()"
        @keydown.arrow-up.prevent="prev()"
        @keydown.enter.prevent="applyActive()"
        @keydown.escape.prevent="handleEscape()"
    />

    <!-- Clear button -->
    <x-ui.clear-button
        @mousedown.prevent.stop="clear()"
        aria-label="Clear input"
    />

    <!-- Suggest Panel -->
    <div
        class="debug-suggest-select-panel"
        x-show="open"
        x-cloak
        :style="panelStyle"
    >
        <ul role="listbox">

            <!-- Optionen -->
            <template x-for="(opt, index) in safeOptions()" :key="opt">
                <li
                    class="debug-suggest-select-option"
                    :class="{ 'is-active': index === activeIndex }"
                    @mousedown.prevent="select(index)"
                >
                    <span x-html="highlight(opt)"></span>
                </li>
            </template>

            <!-- No results -->
            <template x-if="safeOptions().length === 0">
                <li class="debug-suggest-select-option is-empty">
                    <span class="text-muted">
                        Keine Treffer
                    </span>
                </li>
            </template>

        </ul>
    </div>
</div>
