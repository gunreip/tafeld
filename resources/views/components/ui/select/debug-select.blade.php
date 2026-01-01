@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/select/debug-select.blade.php -->
    <!-- {{ $__path }} -->
@endif

<select
    {{ $attributes->merge([
        'class' => 'input-base rounded-md'
    ]) }}
>
    @foreach ($options as $option)
        <x-ui.select.options.select-option
            :value="$option['value']"
            @class([$option['class'] ?? null])
        >
            {{ $option['label'] }}
        </x-ui.select.options.select-option>
    @endforeach
</select>
