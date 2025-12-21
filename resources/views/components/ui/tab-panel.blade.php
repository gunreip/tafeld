{{-- tafeld/resources/views/components/ui/tab-panel.blade.php --}}

@props(['id'])

<div x-show="$parent.active === '{{ $id }}'" x-transition class="w-full">
    {{ $slot }}
</div>
