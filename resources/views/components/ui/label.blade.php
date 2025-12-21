{{-- tafeld/resources/views/components/ui/label.blade.php --}}

@props([
    'for' => null,
])

<label @if ($for) for="{{ $for }}" @endif
    {{ $attributes->merge([
        'class' => 'block mb-1 font-medium text-default',
    ]) }}>
    {{ $slot }}
</label>
