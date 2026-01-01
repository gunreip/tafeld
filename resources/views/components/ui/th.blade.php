@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/th.blade.php -->
    <!-- {{ $__path }} -->
@endif

{{-- table-th --}}

@props(['sortable' => false, 'sortField' => null])

<th {{ $attributes->merge(['class' => 'px-4 py-3 font-semibold text-xs uppercase tracking-wider']) }}>
    <div class="flex items-center gap-1">
        {{ $slot }}

        @if ($sortable && $sortField)
            <button type="button" wire:click="sortBy('{{ $sortField }}')" class="text-muted hover:text-default">
                <x-heroicon-o-chevron-up-down class="w-4 h-4" />
            </button>
        @endif
    </div>
</th>
