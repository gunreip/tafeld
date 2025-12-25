@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/select/options/select-option.blade.php -->
    <!-- {{ $__path }} -->
@endif

@props([
    'index',
    'value',
    'icon' => null,
    'class' => '',
])

<li
    class="debug-custom-select-option"
    role="option"
    :class="{ 'is-active': activeIndex === {{ $index }} }"
    :aria-selected="activeIndex === {{ $index }}"
    @mouseenter="activate({{ $index }})"
    @click="activate({{ $index }}); closeDropdown()"
    data-value="{{ $value }}"
>
    <div class="debug-custom-select-option-inner {{ $class }}">
        @if(!empty($icon))
            <x-ui.icon
                :name="$icon"
                class="debug-custom-select-option-icon"
            />
        @endif

        <span class="debug-custom-select-option-label">
            {{ $slot }}
        </span>
    </div>
</li>
