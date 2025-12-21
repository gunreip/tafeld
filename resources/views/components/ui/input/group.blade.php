{{-- tafeld/resources/views/components/ui/input/group.blade.php --}}

@props([
    'field' => null,
    'icon' => null,
    // 'disableBrowserAutocomplete' => false,
    'label' => null,
    'labelWidth' => 'w-32',
    'id' => null,
])

@php
    use Illuminate\Support\Str;

    // Label width auto-detection
    $isTwWidth = Str::startsWith($labelWidth, 'w-');
    $labelWidthClass = $isTwWidth ? $labelWidth : null;
    $labelWidthStyle = $isTwWidth ? null : "width: {$labelWidth}";

    // Error handling
    $hasError = $field && $errors->has($field);

    $border = $hasError ? 'border border-danger-500' : 'border border-default';
    $iconColor = $hasError ? 'text-danger-600' : 'text-muted';
    $labelColor = $hasError ? 'text-danger-600' : 'text-gray-900';
    $inputBorder = $hasError ? 'focus:border-danger-500' : 'focus:border-brand-100';

@endphp

<div class="rounded-lg overflow-visible bg-card {{ $border }}">
    <div class="grid grid-cols-[auto_auto_1fr] items-stretch">

        {{-- Icon --}}
        <div class="rounded-l-lg px-2 py-2 bg-elevated {{ $iconColor }}">
            @if ($icon)
                <x-ui.icon :name="$icon" size="lg" />
            @endif
        </div>

        {{-- Label --}}
        <label for="{{ $id }}"
            class="px-3 py-2 bg-elevated text-default font-light border-l border-default cursor-pointer {{ $labelColor }} {{ $labelWidthClass }}"
            @if ($labelWidthStyle) style="{{ $labelWidthStyle }}" @endif>
            {{ $label }}
        </label>

        {{-- Input --}}
        <div class="flex-1 border-l border-default">

            {{-- NEU: Slot für beliebig viele Eingabefelder --}}
            {!! $slot !!}

        </div>

    </div>
</div>
