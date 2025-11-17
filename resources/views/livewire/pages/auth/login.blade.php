<!-- tafeld/resources/views/livewire/pages/auth/login.blade.php -->

<div
    class="w-full sm:max-w-md mx-auto mt-10 px-6 py-8 bg-card text-default shadow-sm rounded-lg border border-default space-y-6">

    <x-logo variant="die-tafeln" width="200" class="mx-auto" />

    <h1 class="text-2xl font-semibold text-default">
        Anmelden
    </h1>

    @if (session('status'))
        <div class="mb-4 text-sm text-success">
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div class="text-danger mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit="login" class="space-y-6">

        <div>
            <label class="block text-sm font-medium text-default mb-1">
                E-Mail
            </label>
            <input type="email" wire:model="email" required autofocus
                class="w-full rounded-md bg-card text-default border border-default
                       focus:ring-brand-500 focus:border-brand-500" />
            @error('email')
                <p class="text-danger text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <div class="flex justify-between items-center">
                <label class="block text-sm font-medium text-default mb-1">
                    Passwort
                </label>
                <a href="{{ route('password.request') }}" wire:navigate class="text-sm text-brand-500 hover:underline">
                    Passwort vergessen?
                </a>
            </div>

            <input type="password" wire:model="password" required
                class="w-full rounded-md bg-card text-default border border-default
                       focus:ring-brand-500 focus:border-brand-500" />
            @error('password')
                <p class="text-danger text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center">
            <input type="checkbox" wire:model="remember"
                class="rounded bg-card border-default text-brand-500
                       focus:ring-brand-500" />
            <span class="ml-2 text-sm text-default">
                Angemeldet bleiben
            </span>
        </div>

        <div>
            <button type="submit" class="w-full py-2 flex justify-center rounded-md btn-brand">
                Anmelden
            </button>
        </div>

        @if ($errors->any())
            <div class="mt-6 text-center">
                <p class="mb-2 text-sm text-muted">
                    Noch kein Konto?
                </p>

                <a href="{{ route('register') }}" class="w-full py-2 flex justify-center rounded-md btn-brand">
                    Registrieren
                </a>
            </div>
        @endif
    </form>

</div>
