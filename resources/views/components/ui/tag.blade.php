{{-- tafeld/resources/views/components/ui/tag.blade.php --}}

@props([
    'color' => 'default', // default | brand | success | warning | danger | info
    'removable' => false, // ob der "X"-Button angezeigt wird
    'onRemove' => null, // Livewire-Aktion: z.B. removeTag($tagId)
    'rounded' => 'full', // sm | md | lg | full
])

@php
    // Farbvarianten auf Basis deiner Theme-Variablen
    $colors = [
        'default' => 'bg-hover text-default border border-default',
        'brand' => 'bg-brand-soft text-brand-500 border border-brand-300',
        'success' => 'bg-success-soft text-success border border-success',
        'warning' => 'bg-warning-soft text-warning border border-warning',
        'danger' => 'bg-danger-soft text-danger border border-danger',
        'info' => 'bg-info-soft text-info border border-info',
    ];

    $roundedMap = [
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'full' => 'rounded-full',
    ];
@endphp

<span
    class="inline-flex items-center gap-2 px-3 py-1 text-xs font-medium select-none
             {{ $colors[$color] ?? $colors['default'] }}
             {{ $roundedMap[$rounded] ?? $roundedMap['full'] }}">

    {{-- Label --}}
    <span>{{ $slot }}</span>

    {{-- Optional Remove Button --}}
    @if ($removable)
        <button type="button" @if ($onRemove) wire:click="{{ $onRemove }}" @endif
            class="text-muted hover:text-default hover:bg-hover rounded-full w-4 h-4 flex items-center justify-center transition">
            ✕
        </button>
    @endif

</span>
