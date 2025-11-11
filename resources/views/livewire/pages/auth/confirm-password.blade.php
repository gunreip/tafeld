<div class="w-full sm:max-w-md mx-auto mt-10 px-6 py-8 bg-white dark:bg-gray-800 shadow-lg rounded-lg">

    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">
        Passwort bestätigen
    </h1>

    <p class="text-sm text-gray-700 dark:text-gray-300 mb-6">
        Bitte gib zur Bestätigung dein Passwort erneut ein.
    </p>

    <form wire:submit="confirm" class="space-y-6">

        {{-- Passwort --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Passwort
            </label>
            <input type="password" wire:model="password" required
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100
                       focus:border-indigo-500 focus:ring-indigo-500" />
        </div>

        @if ($errorMessage)
            <p class="text-red-500 text-sm">{{ $errorMessage }}</p>
        @endif

        <div>
            <button type="submit"
                class="w-full py-2 flex justify-center rounded-md bg-indigo-600 text-white
                           hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600
                           focus:ring-2 focus:ring-indigo-500">
                Bestätigen
            </button>
        </div>

    </form>

</div>
