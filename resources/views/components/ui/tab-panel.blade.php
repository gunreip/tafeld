@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/tab-panel.blade.php -->
    <!-- {{ $__path }} -->
@endif

@props(['id'])

<div x-show="$parent.active === '{{ $id }}'" x-transition class="w-full">
    {{ $slot }}
</div>
