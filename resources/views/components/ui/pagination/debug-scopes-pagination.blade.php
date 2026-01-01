@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/pagination/debug-scopes-pagination.blade.php -->
    <!-- {{ $__path }} -->
@endif

{{-- Debug Pagination (cursor-based, Livewire-safe) --}}

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

    {{-- LEFT: Page info --}}
    <div class="text-sm text-muted">
        Seite {{ $page }}
        @if(!is_null($total))
            · {{ $total }} Einträge
        @endif
    </div>

    {{-- CENTER: Navigation --}}
    <div class="flex items-center gap-2">

        {{-- Previous --}}
        @if ($page <= 1)
            <span class="px-3 py-2 text-sm rounded border border-default bg-elevated text-muted cursor-not-allowed">
                Zurück
            </span>
        @else
            <button
                wire:click="previousPage"
                wire:loading.attr="disabled"
                class="px-3 py-2 text-sm rounded border border-default bg-card hover:bg-hover transition"
            >
                Zurück
            </button>
        @endif

        <span class="px-3 py-2 text-sm rounded bg-card border border-default">
            {{ $page }}
        </span>

        {{-- Next --}}
        @if ($hasMore)
            <button
                wire:click="nextPage"
                wire:loading.attr="disabled"
                class="px-3 py-2 text-sm rounded border border-default bg-card hover:bg-hover transition"
            >
                Weiter
            </button>
        @else
            <span class="px-3 py-2 text-sm rounded border border-default bg-elevated text-muted cursor-not-allowed">
                Weiter
            </span>
        @endif
    </div>

    {{-- RIGHT: Per Page --}}
    <div class="flex items-center gap-2 text-sm">
        <label class="text-muted">Pro Seite</label>
        <select
            wire:model.live="perPage"
            class="rounded border border-default bg-card px-2 py-1"
        >
            @foreach ($perPageOptions as $opt)
                <option value="{{ $opt }}">{{ $opt }}</option>
            @endforeach
        </select>
    </div>

</div>
