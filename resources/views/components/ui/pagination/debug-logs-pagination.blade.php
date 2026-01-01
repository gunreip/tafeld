@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/pagination/debug-logs-pagination.blade.php -->
    <!-- {{ $__path }} -->
@endif

@php
    $current = $paginator->currentPage();
    $last    = $paginator->lastPage();
    $radius  = 2;

    $start = max(1, $current - $radius);
    $end   = min($last, $current + $radius);
@endphp

<div class="mt-6 debug-pagination">

    {{-- Per Page --}}
    <div class="flex items-center gap-3">
        <span class="debug-pagination-meta flex items-center gap-1">
            <x-heroicon-o-list-bullet class="w-4 h-4 text-muted" />
            <span>Pro Seite:</span>
        </span>

        <div class="inline-flex rounded-lg border border-default bg-card input-group-divider overflow-hidden">
            @foreach ([10, 20, 50] as $opt)
                @if ($paginator->perPage() === $opt)
                    <span class="debug-pagination-item is-active rounded-none">
                        {{ $opt }}
                    </span>
                @else
                    <button
                        wire:click="$set('perPage', {{ $opt }})"
                        class="debug-pagination-item rounded-none">
                        {{ $opt }}
                    </button>
                @endif
            @endforeach
        </div>
    </div>

    {{-- Navigation (Zurück · Seiten · Weiter) --}}
    <div class="flex items-center gap-3">

        {{-- Zurück --}}
        <div class="inline-flex rounded-lg border border-default bg-card overflow-hidden">
            <button
                wire:click="previousPage"
                @disabled($paginator->onFirstPage())
                class="debug-pagination-item rounded-none flex items-center gap-1"
                @class(['is-disabled' => $paginator->onFirstPage()])>
                <x-heroicon-o-chevron-left class="w-4 h-4" />
                <span>Zurück</span>
            </button>
        </div>

        {{-- Seiten --}}
        <div class="debug-pagination-group font-mono">

            {{-- Seite 1 --}}
            @if ($start > 1)
                <button
                    wire:click="gotoPage(1)"
                    class="debug-pagination-item">
                    1
                </button>

                @if ($start > 2)
                    <span class="px-2 text-muted">…</span>
                @endif
            @endif

            {{-- Fenster --}}
            @for ($page = $start; $page <= $end; $page++)
                @if ($page === $current)
                    <span class="debug-pagination-item is-active">
                        {{ $page }}
                    </span>
                @else
                    <button
                        wire:click="gotoPage({{ $page }})"
                        class="debug-pagination-item">
                        {{ $page }}
                    </button>
                @endif
            @endfor

            {{-- Letzte Seite --}}
            @if ($end < $last)
                @if ($end < $last - 1)
                    <span class="px-2 text-muted">…</span>
                @endif

                <button
                    wire:click="gotoPage({{ $last }})"
                    class="debug-pagination-item">
                    {{ $last }}
                </button>
            @endif

        </div>

        {{-- Weiter --}}
        <div class="inline-flex rounded-lg border border-default bg-card overflow-hidden">
            <button
                wire:click="nextPage"
                @disabled(! $paginator->hasMorePages())
                class="debug-pagination-item rounded-none flex items-center gap-1"
                @class(['is-disabled' => ! $paginator->hasMorePages()])>
                <span>Weiter</span>
                <x-heroicon-o-chevron-right class="w-4 h-4" />
            </button>
        </div>

    </div>

</div>
