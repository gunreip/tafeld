<div class="max-w-3xl mx-auto py-16 px-6">
    <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100">
        Tafeld
    </h1>

    <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">
        Übersichtliche lokale Anwendung. Bitte melden Sie sich an.
    </p>

    <div class="mt-8 grid sm:grid-cols-2 gap-6">
        {{-- Login --}}
        <a href="{{ route('login') }}" wire:navigate
            class="block rounded-lg border border-gray-300 dark:border-gray-700 p-6 hover:bg-gray-100 dark:hover:bg-gray-800">
            <h2 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Login</h2>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Bestehendes Konto verwenden.</p>
        </a>

        {{-- Registrieren --}}
        <a href="{{ route('register') }}" wire:navigate
            class="block rounded-lg border border-gray-300 dark:border-gray-700 p-6 hover:bg-gray-100 dark:hover:bg-gray-800">
            <h2 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">Registrieren</h2>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Neues Benutzerkonto erstellen.</p>
        </a>
    </div>
</div>
