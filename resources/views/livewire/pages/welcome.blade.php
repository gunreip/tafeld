@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/livewire/pages/welcome.blade.php -->
    <!-- {{ $__path }} -->
@endif

<div class="max-w-3xl mx-auto py-16 px-6 bg-surface text-default">

    <h1 class="text-4xl font-bold text-default">
        Tafeld
    </h1>

    <p class="mt-4 text-lg text-muted">
        Übersichtliche lokale Anwendung. Bitte melden Sie sich an.
    </p>

    <div class="mt-8 grid sm:grid-cols-2 gap-6">

        {{-- Login --}}
        <a href="{{ route('login') }}" wire:navigate
            class="block rounded-lg border border-default bg-card p-6 hover:bg-hover transition">
            <h2 class="text-xl font-semibold mb-2 text-default">Login</h2>
            <p class="text-muted text-sm">Bestehendes Konto verwenden.</p>
        </a>

        {{-- Registrieren --}}
        <a href="{{ route('register') }}" wire:navigate
            class="block rounded-lg border border-default bg-card p-6 hover:bg-hover transition">
            <h2 class="text-xl font-semibold mb-2 text-default">Registrieren</h2>
            <p class="text-muted text-sm">Neues Benutzerkonto erstellen.</p>
        </a>

    </div>

</div>
