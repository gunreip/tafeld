@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/select/debug-suggest-select.blade.php -->
    <!-- {{ $__path }} -->
@endif

<!-- DEBUG: Placeholder-Analyse -->
<!-- placeholder raw: {{ var_export($placeholder ?? null, true) }} -->

<div
    class="debug-suggest-input"
    data-wire-model="{{ $attributes->wire('model') instanceof \Livewire\WireDirective ? $attributes->wire('model')->value() : $attributes->wire('model') }}"
    x-data="debugSuggestInput({
        value: @entangle($attributes->wire('model')),
        options: @js($options)
    })"
    x-init="init()"
    @keydown.arrow-down.prevent="open && next()"
    @keydown.arrow-up.prevent="open && prev()"
    @keydown.enter.prevent="applyActive()"
    @keydown.escape.prevent="close()"
    @click.outside="close()"
>

    <!-- Input -->
    <input
        type="text"
        class="debug-suggest-input-field"
        x-model="value"
        @input="onInput()"
        @focus="openIfHasOptions()"
        placeholder="{{ $placeholder ?? 'Scope …' }}"
        {{ $attributes->wire('model') }}
    />

    <!-- Clear button -->
    <x-ui.clear-button
        x-show="hasValue()"
        @click.stop="clear()"
        aria-label="Clear input"
    />

    <!-- Suggest Panel -->
    <div
        class="debug-suggest-input-panel"
        x-show="open"
        x-cloak
        :style="panelStyle"
    >
        <ul role="listbox">
            <template x-for="(opt, index) in safeOptions()" :key="opt">
                <li
                    class="debug-suggest-input-option"
                    :class="{ 'is-active': index === activeIndex }"
                    @mousedown.prevent="select(index)"
                >
                    <span x-html="highlight(opt)"></span>
                </li>
            </template>
        </ul>
    </div>

</div>
