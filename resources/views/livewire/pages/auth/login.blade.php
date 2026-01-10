@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/livewire/pages/auth/login.blade.php -->
    <!-- {{ $__path }} -->
@endif

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

        {{-- Email --}}
        <div>
            <x-ui.label for="email">E-Mail</x-ui.label>

            <x-ui.input type="email" name="email" id="email" wire:model="email" required autofocus
                class="w-full" />

            @error('email')
                <p class="text-danger text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label class="block font-medium text-default mb-1">
                Passwort
            </label>

            <x-ui.input type="password" name="password" wire:model="password" required show-password
                class="w-full rounded px-3 py-2 bg-card text-default border border-default
               focus:ring-brand-500 focus:border-brand-500" />

            <x-ui.link href="{{ route('password.request') }}" wire:navigate class="block mt-1 text-right text-sm">
                Passwort vergessen?
            </x-ui.link>

            @error('password')
                <p class="text-danger text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember --}}
        <div>
            <x-ui.checkbox model="remember">Angemeldet bleiben?</x-ui.checkbox>
        </div>

        {{-- Submit --}}
        <div>
            <x-ui.button type="submit" class="w-full justify-center">Anmelden</x-ui.button>
        </div>

        {{-- Register --}}
        @if ($errors->any())
            <div class="mt-6 text-center">
                <p class="mb-2 text-sm text-muted">
                    Noch kein Konto?
                </p>

                <x-ui.link href="{{ route('register') }}" class="w-full py-2 flex justify-center rounded-md btn-brand">
                    Registrieren
                </x-ui.link>
            </div>
        @endif
    </form>
</div>
