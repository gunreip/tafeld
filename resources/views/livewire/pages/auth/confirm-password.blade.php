@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/livewire/pages/auth/confirm-password.blade.php -->
    <!-- {{ $__path }} -->
@endif

<div
    class="w-full sm:max-w-md mx-auto mt-10 px-6 py-8 bg-card text-default
           shadow-sm rounded-lg border border-default space-y-6">

    <h1 class="text-2xl font-semibold text-default">
        Passwort bestätigen
    </h1>

    <p class="text-sm text-muted">
        Bitte gib zur Bestätigung dein Passwort erneut ein.
    </p>

    <form wire:submit="confirm" class="space-y-6">

        {{-- Passwort --}}
        <div>
            <x-ui.label for="password">Passwort</x-ui.label>

            <x-ui.input type="password" name="password" id="password" wire:model="password" required show-password
                class="w-full rounded-md" />

            @error('password')
                <p class="text-danger text-sm mt-1">{{ $message }}</p>
            @enderror

            @if ($errorMessage)
                <p class="text-danger text-sm mt-1">{{ $errorMessage }}</p>
            @endif
        </div>

        <x-ui.button type="submit" class="w-full justify-center">Bestätigen</x-ui.button>

    </form>

</div>
