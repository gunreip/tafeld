<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'tafeld') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-base-100 min-h-screen">
    <header class="navbar bg-neutral text-neutral-content shadow-lg">
        <div class="flex-1 px-4 font-bold text-xl">
            <x-heroicon-o-rectangle-stack class="w-6 h-6 inline-block mr-2 text-primary" />
            <a href="{{ route('dashboard') }}">tafeld</a>
        </div>

        <nav class="flex-none flex items-center gap-2">
            <a href="{{ route('dashboard') }}"
                class="btn btn-sm btn-ghost {{ request()->routeIs('dashboard') ? 'btn-active' : '' }}">
                <x-heroicon-o-home class="w-5 h-5 mr-1" /> Dashboard
            </a>
            <a href="{{ route('personal.index') }}"
                class="btn btn-sm btn-ghost {{ request()->routeIs('personal.*') ? 'btn-active' : '' }}">
                <x-heroicon-o-user-group class="w-5 h-5 mr-1" /> Personal
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-error text-white">
                    <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5 mr-1" /> Logout
                </button>
            </form>
        </nav>
    </header>

    <main class="p-6">
        {{ $slot }}
    </main>

    <footer class="footer footer-center p-4 bg-base-200 text-base-content/60">
        <p>© {{ date('Y') }} tafeld</p>
    </footer>
    @livewireScripts
</body>

</html>
