@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/form.blade.php -->
    <!-- {{ $__path }} -->
@endif

@props([
    'submit', // z. B. "save", "login", "register"
    'card' => false, // wenn true → automatisch in Card rendern
    'padding' => 'space-y-6', // spacing innerhalb
    'autosave' => false, // optionaler Live-Speicher
])

@php
    $wrapperClasses = $card ? 'bg-card border border-default rounded-lg shadow-sm p-6 ' . $padding : $padding;
@endphp

<form wire:submit="{{ $submit }}" {{ $attributes->merge(['class' => $wrapperClasses]) }}>

    {{-- Fehlerliste (global) --}}
    @if ($errors->any())
        <div class="p-3 bg-danger-soft text-danger rounded border border-danger/40">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Inhalt --}}
    <div class="space-y-4">
        {{ $slot }}
    </div>

    {{-- Autosave Hinweis --}}
    @if ($autosave)
        <p class="text-xs text-muted italic">
            Änderungen werden automatisch gespeichert …
        </p>
    @endif

    {{-- Loading Overlay --}}
    <div wire:loading.delay.inline wire:target="{{ $submit }}" class="text-center text-muted text-sm">
        <span class="animate-pulse">Bitte warten …</span>
    </div>

</form>
