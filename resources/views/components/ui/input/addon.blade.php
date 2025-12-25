@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/input/addon.blade.php -->
    <!-- {{ $__path }} -->
@endif

@props([
    'prefix' => null, // Text oder Icon links
    'suffix' => null, // Text oder Icon rechts
    'iconLeft' => null, // z. B. "magnifying-glass"
    'iconRight' => null,
])

<div class="relative flex items-center w-full">

    {{-- LEFT PREFIX --}}
    @if ($prefix)
        <span class="absolute left-3 text-muted text-sm select-none">
            {{ $prefix }}
        </span>
    @endif

    {{-- LEFT ICON --}}
    @if ($iconLeft)
        <x-dynamic-component :component="'heroicon-o-' . $iconLeft" class="absolute left-3 w-5 h-5 text-muted" />
    @endif

    {{-- INPUT (Slot) --}}
    <div class="w-full">
        {{ $slot }}
    </div>

    {{-- RIGHT SUFFIX --}}
    @if ($suffix)
        <span class="absolute right-3 text-muted text-sm select-none">
            {{ $suffix }}
        </span>
    @endif

    {{-- RIGHT ICON --}}
    @if ($iconRight)
        <x-dynamic-component :component="'heroicon-o-' . $iconRight" class="absolute right-3 w-5 h-5 text-muted" />
    @endif

</div>
