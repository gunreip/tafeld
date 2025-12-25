@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/table/th.blade.php -->
    <!-- {{ $__path }} -->
@endif

@props([
    'sortable'   => false,
    'sortField' => null,
    'direction' => null, // asc | desc | null
])

<th {{ $attributes->merge([
    'class' => 'px-4 py-3 text-lg font-semibold uppercase tracking-wider text-muted bg-elevated'
]) }}>
    <div class="flex items-center gap-1">
        {{ $slot }}

        @if ($sortable && $sortField)
            <span class="text-muted">
                @if ($direction === 'asc')
                    ▲
                @elseif ($direction === 'desc')
                    ▼
                @else
                    ⇅
                @endif
            </span>
        @endif
    </div>
</th>
