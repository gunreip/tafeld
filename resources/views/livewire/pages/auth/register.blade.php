<!-- tafeld/resources/views/livewire/pages/auth/register.blade.php -->

<div
    class="w-full sm:max-w-md mx-auto mt-10 px-6 py-8 bg-card text-default shadow-sm rounded-lg border border-default space-y-6">

    <h1 class="text-2xl font-semibold text-default">
        Registrieren
    </h1>

    <form wire:submit="register" class="space-y-6">

        {{-- Name --}}
        <div>
            <label class="block font-medium text-default mb-1">
                Name
            </label>
            <input type="text" wire:model="name" required
                class="w-full rounded px-3 py-2 bg-card text-default border border-default
                       focus:ring-brand-500 focus:border-brand-500" />
            @error('name')
                <p class="text-danger text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="block font-medium text-default mb-1">
                E-Mail
            </label>
            <input type="email" wire:model="email" required
                class="w-full rounded px-3 py-2 bg-card text-default border border-default
                       focus:ring-brand-500 focus:border-brand-500" />
            @error('email')
                <p class="text-danger text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label class="block font-medium text-default mb-1">
                Passwort
            </label>
            <input type="password" wire:model="password" required
                class="w-full rounded px-3 py-2 bg-card text-default border border-default
                       focus:ring-brand-500 focus:border-brand-500" />
            @error('password')
                <p class="text-danger text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password Confirmation --}}
        <div>
            <label class="block font-medium text-default mb-1">
                Passwort bestätigen
            </label>
            <input type="password" wire:model="password_confirmation" required
                class="w-full rounded px-3 py-2 bg-card text-default border border-default
                       focus:ring-brand-500 focus:border-brand-500" />
        </div>

        {{-- Submit Button --}}
        <div>
            <button type="submit" class="w-full py-2 flex justify-center rounded-md btn-brand">
                Registrieren
            </button>
        </div>

        {{-- Link zu Login --}}
        <p class="text-sm text-center text-muted">
            Bereits registriert?
            <a href="{{ route('login') }}" wire:navigate class="text-brand-500 hover:underline">
                Anmelden
            </a>
        </p>

    </form>

</div>
