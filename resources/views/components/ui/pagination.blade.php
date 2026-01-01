@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/pagination.blade.php -->
    <!-- {{ $__path }} -->
@endif

@props(['paginator'])

@if ($paginator->hasPages())
    <nav class="flex items-center justify-between mt-6 select-none">

        {{-- PREVIOUS --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-2 text-sm rounded bg-elevated text-muted border border-default cursor-not-allowed">
                Zurück
            </span>
        @else
            <button wire:click="previousPage" wire:loading.attr="disabled"
                class="px-3 py-2 text-sm rounded bg-card text-default border border-default hover:bg-hover">
                Zurück
            </button>
        @endif


        {{-- PAGE NUMBERS --}}
        <div class="flex items-center gap-1">
            @foreach ($elements as $element)
                {{-- "..." separator --}}
                @if (is_string($element))
                    <span class="px-3 py-2 text-sm text-muted">{{ $element }}</span>

                    {{-- Page number array --}}
                @elseif (is_array($element))
                    @foreach ($element as $page => $url)
                        {{-- Active page --}}
                        @if ($page == $paginator->currentPage())
                            <span
                                class="px-3 py-2 text-sm font-semibold rounded bg-brand-500/20 text-brand-500 border border-brand-500">
                                {{ $page }}
                            </span>

                            {{-- Clickable page --}}
                        @else
                            <button wire:click="gotoPage({{ $page }})" wire:loading.attr="disabled"
                                class="px-3 py-2 text-sm rounded bg-card text-default border border-default hover:bg-hover">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>


        {{-- NEXT --}}
        @if ($paginator->hasMorePages())
            <button wire:click="nextPage" wire:loading.attr="disabled"
                class="px-3 py-2 text-sm rounded bg-card text-default border border-default hover:bg-hover">
                Weiter
            </button>
        @else
            <span class="px-3 py-2 text-sm rounded bg-elevated text-muted border border-default cursor-not-allowed">
                Weiter
            </span>
        @endif

    </nav>
@endif
