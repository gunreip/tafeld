<div class="w-full sm:max-w-md mx-auto mt-10 px-6 py-8 bg-white dark:bg-gray-800 shadow-lg rounded-lg">

    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">
        Registrieren
    </h1>

    <form wire:submit="register" class="space-y-6">

        {{-- Name --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Name
            </label>
            <input type="text" wire:model="name" required
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100
                          focus:border-indigo-500 focus:ring-indigo-500" />
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                E-Mail
            </label>
            <input type="email" wire:model="email" required
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100
                          focus:border-indigo-500 focus:ring-indigo-500" />
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Passwort
            </label>
            <input type="password" wire:model="password" required
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100
                          focus:border-indigo-500 focus:ring-indigo-500" />
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password Confirmation --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Passwort bestätigen
            </label>
            <input type="password" wire:model="password_confirmation" required
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100
                          focus:border-indigo-500 focus:ring-indigo-500" />
        </div>

        <div>
            <button type="submit"
                class="w-full py-2 flex justify-center rounded-md bg-indigo-600 text-white
                           hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600
                           focus:ring-2 focus:ring-indigo-500">
                Registrieren
            </button>
        </div>

        <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
            Bereits registriert?
            <a href="{{ route('login') }}" wire:navigate class="text-indigo-600 dark:text-indigo-400 hover:underline">
                Anmelden
            </a>
        </p>

    </form>

</div>
