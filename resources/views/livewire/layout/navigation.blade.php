<!-- tafeld/resources/views/livewire/layout/navigation.blade.php -->
<nav class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
        <a wire:navigate href="/" class="text-xl font-semibold text-gray-900 dark:text-gray-100">
            Tafeld
        </a>

        <div class="flex items-center gap-4">
            <a href="/" wire:navigate
                class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100">
                Startseite
            </a>

            @auth
                <a href="/dashboard" wire:navigate
                    class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100">
                    Dashboard
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" wire:navigate
                    class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100">
                    Login
                </a>
                <a href="{{ route('register') }}" wire:navigate
                    class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100">
                    Registrieren
                </a>
            @endauth

            <button @click="document.documentElement.classList.toggle('dark')"
                class="px-2 py-1 rounded border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300">
                Theme
            </button>
        </div>
    </div>
</nav>
