@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/livewire/pages/auth/reset-password.blade.php -->
    <!-- {{ $__path }} -->
@endif

<div
    class="w-full sm:max-w-md mx-auto mt-10 px-6 py-8 bg-card text-default
           shadow-sm rounded-lg border border-default space-y-6">

    <h1 class="text-2xl font-semibold text-default">
        Neues Passwort setzen
    </h1>

    @if (session('status'))
        <div class="text-sm text-success">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="resetPassword" class="space-y-6">

        {{-- Email --}}
        <div>
            <x-ui.label for="email">E-Mail</x-ui.label>
            <x-ui.input type="email" name="email" id="email" wire:model="email" required class="w-full" />
            @error('email')
                <p class="text-danger text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Passwort --}}
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

        {{-- Passwort bestätigen --}}
        <div>
            <label class="block font-medium text-default mb-1">
                Passwort bestätigen
            </label>
            <x-ui.input type="password" name="password_confirmation" wire:model="password_confirmation" required show-password
                class="w-full rounded px-3 py-2 bg-card text-default border border-default" />
        </div>

        <x-ui.button type="submit" class="w-full justify-center">Passwort setzen</x-ui.button>

    </form>

</div>
