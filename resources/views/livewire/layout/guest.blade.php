<!DOCTYPE html>
<html lang="de" x-data="darkModeController()" x-init="init()" wire:ignore>

<!-- tafeld/resources/views/livewire/layout/guest.blade.php -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>{{ config('app.name', 'tafeld') }}</title>

    <script>
        function darkModeController() {
            return {
                isDark: false,

                init() {
                    const saved = localStorage.getItem('dark-mode');
                    this.isDark = saved === 'true';
                    document.documentElement.classList.toggle('dark', this.isDark);
                },

                toggle() {
                    this.isDark = !this.isDark;
                    localStorage.setItem('dark-mode', this.isDark);
                    document.documentElement.classList.toggle('dark', this.isDark);
                },
            }
        }
    </script>
</head>

<body class="bg-surface text-default">

    <div class="min-h-screen flex flex-col items-center justify-center px-4">
        <div class="w-full max-w-md space-y-6">

            <header class="flex items-center justify-between">
                <a href="{{ route('welcome') }}" class="text-lg font-semibold text-default hover:text-brand-500">
                    {{ config('app.name', 'tafeld') }}
                </a>

                <button type="button"
                    class="inline-flex items-center justify-center rounded-full p-2
                           text-muted hover:text-default hover:bg-hover
                           focus:outline-none focus:ring-2 focus:ring-brand-500"
                    @click="toggle()">
                    <span class="sr-only">Dark Mode umschalten</span>

                    <x-heroicon-o-moon class="w-5 h-5 text-muted" x-show="!isDark" />
                    <x-heroicon-o-sun class="w-5 h-5 text-muted" x-show="isDark" />
                </button>
            </header>

            <main class="bg-card border border-default rounded-xl shadow-sm p-6">
                {{ $slot }}
            </main>

        </div>
    </div>

</body>

</html>
