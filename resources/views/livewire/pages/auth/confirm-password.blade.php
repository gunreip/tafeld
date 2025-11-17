<!-- tafeld/resources/views/livewire/pages/auth/confirm-password.blade.php -->

<div
    class="w-full sm:max-w-md mx-auto mt-10 px-6 py-8 bg-card text-default shadow-sm rounded-lg border border-default space-y-6">

    <h1 class="text-2xl font-semibold text-default">
        Passwort bestätigen
    </h1>

    <p class="text-sm text-muted">
        Bitte gib zur Bestätigung dein Passwort erneut ein.
    </p>

    <form wire:submit="confirm" class="space-y-6">

        {{-- Passwort --}}
        <div>
            <label class="block text-sm font-medium text-default mb-1">
                Passwort
            </label>
            <input type="password" wire:model="password" required
                class="w-full rounded-md bg-card text-default border border-default
                       focus:ring-brand-500 focus:border-brand-500" />
        </div>

        @if ($errorMessage)
            <p class="text-danger text-sm">{{ $errorMessage }}</p>
        @endif

        <div>
            <button type="submit" class="w-full py-2 flex justify-center rounded-md btn-brand">
                Bestätigen
            </button>
        </div>

    </form>

</div>
