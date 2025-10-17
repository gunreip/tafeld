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
    <header class="navbar bg-base-200 shadow">
        <div class="flex-1 px-4 text-xl font-bold text-primary">
            <a href="{{ route('dashboard') }}">tafeld</a>
        </div>
        <nav class="flex-none">
            <ul class="menu menu-horizontal px-1">
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active font-bold' : '' }}">Dashboard</a>
                </li>
                <li>
                    <a href="{{ route('personal.index') }}" class="{{ request()->routeIs('personal.*') ? 'active font-bold' : '' }}">Personal</a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-error text-white ml-4">Logout</button>
                    </form>
                </li>
            </ul>
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
