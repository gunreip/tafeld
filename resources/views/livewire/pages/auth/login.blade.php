<div class="w-full sm:max-w-md mx-auto mt-10 px-6 py-8 bg-white dark:bg-gray-800 shadow-lg rounded-lg">

    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">
        Anmelden
    </h1>

    @if (session('status'))
        <div class="mb-4 text-sm text-green-600 dark:text-green-400">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="login" class="space-y-6">

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                E-Mail
            </label>
            <input type="email" wire:model="email" required autofocus
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700
                          dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" />
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <div class="flex justify-between items-center">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Passwort
                </label>
                <a href="{{ route('password.request') }}" wire:navigate
                    class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                    Passwort vergessen?
                </a>
            </div>

            <input type="password" wire:model="password" required
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700
                          dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" />
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center">
            <input type="checkbox" wire:model="remember"
                class="rounded border-gray-300 dark:border-gray-600 text-indigo-600
                          focus:ring-indigo-500" />
            <span class="ml-2 text-gray-700 dark:text-gray-300 text-sm">
                Angemeldet bleiben
            </span>
        </div>

        <div>
            <button type="submit"
                class="w-full py-2 flex justify-center rounded-md bg-indigo-600 text-white
                           hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600
                           focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Anmelden
            </button>
        </div>
    </form>

</div>
