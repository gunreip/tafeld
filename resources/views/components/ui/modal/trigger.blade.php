{{-- tafeld/resources/views/components/ui/modal/trigger.blade.php --}}

@props([
    'modal' => null, // ID des Modals
])

<button
    {{ $attributes->merge([
        'class' => 'px-4 py-2 rounded-lg bg-brand-500 text-white hover:bg-brand-600',
    ]) }}
    @click="$dispatch('open-modal', '{{ $modal }}')">
    {{ $slot }}
</button>
