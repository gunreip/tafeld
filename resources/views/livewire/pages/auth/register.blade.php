@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/livewire/pages/auth/register.blade.php -->
    <!-- {{ $__path }} -->
@endif

<div
    class="w-full sm:max-w-md mx-auto mt-10 px-6 py-8 bg-card text-default shadow-sm rounded-lg border border-default space-y-6">

    <h1 class="text-2xl font-semibold text-default">
        Registrieren
    </h1>

    <form wire:submit="register" class="space-y-6">

        {{-- Name --}}
        <div>
            <x-ui.label for="name">Name</x-ui.label>
            <x-ui.input type="text" name="name" id="name" wire:model="name" required class="w-full" />
            @error('name')
                <p class="text-danger text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <x-ui.label for="email">E-Mail</x-ui.label>
            <x-ui.input type="email" name="email" id="email" wire:model="email" required class="w-full" />
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
                class="w-full rounded px-3 py-2 bg-card text-default border border-default" />
            @error('password')
                <p class="text-danger text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password Confirmation --}}
        <div>
            <label class="block font-medium text-default mb-1">
                Passwort bestätigen
            </label>
            <x-ui.input type="password" name="password_confirmation" wire:model="password_confirmation" required show-password
                class="w-full rounded px-3 py-2 bg-card text-default border border-default" />
        </div>

        {{-- Submit Button --}}
        <div>
            <x-ui.button type="submit" class="w-full justify-center">Registrieren</x-ui.button>
        </div>

        {{-- Link zu Login --}}
        <p class="text-sm text-center text-muted">
            Bereits registriert?
            <x-ui.link href="{{ route('login') }}" wire:navigate class="text-brand-500">Anmelden</x-ui.link>
        </p>

    </form>

</div>
