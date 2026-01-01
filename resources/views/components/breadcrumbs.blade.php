@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/breadcrumbs.blade.php -->
    <!-- {{ $__path }} -->
@endif

<nav class="w-full mt-1 mb-1">
    <ol class="flex items-center gap-1">

        @foreach ($items as $index => $item)
            <li class="flex items-center">

                @php
                    $icon = $item['icon'] ?? null;
                @endphp

                {{-- CLICKABLE BREADCRUMB --}}
                @if (isset($item['url']) && $item['url'])
                    <a href="{{ $item['url'] }}" wire:navigate
                        class="flex items-center gap-1 px-3 py-1.5 rounded-full
                               bg-elevated text-muted border border-default
                               hover:bg-hover hover:text-default
                               transition-all duration-150 ease-out
                               hover:-translate-y-0.5 hover:shadow-sm">

                        @if ($icon)
                            <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-3 h-3" />
                        @endif

                        <span>{{ $item['label'] }}</span>
                    </a>
                @else
                    {{-- ACTIVE BREADCRUMB --}}
                    <span
                        class="flex items-center gap-1 px-3 py-1.5 rounded-full
                                 bg-brand-soft text-brand border border-brand-200
                                 text-xs font-semibold">

                        @if ($icon)
                            <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-3 h-3" />
                        @endif

                        {{ $item['label'] }}
                    </span>
                @endif

                {{-- SEPARATOR ICON --}}
                @if ($index < count($items) - 1)
                    <x-heroicon-o-chevron-right class="w-4 h-4 text-muted mx-1" />
                @endif

            </li>
        @endforeach

    </ol>
</nav>
