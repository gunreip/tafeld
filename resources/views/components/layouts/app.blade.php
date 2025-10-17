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
        <div class="flex-1 px-4 font-bold text-xl text-primary">
            <a href="{{ url('/') }}">tafeld</a>
        </div>
        <div class="flex-none gap-2">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-error text-white">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-sm btn-primary">Login</a>
                <a href="{{ route('register') }}" class="btn btn-sm btn-outline">Registrieren</a>
            @endauth
        </div>
    </header>

    <main class="p-6">
        {{ $slot }}
    </main>

    <footer class="footer footer-center p-4 bg-base-200 text-base-content/60">
        <p>© {{ date('Y') }} tafeld – Datenschutz durch Design</p>
    </footer>

    @livewireScripts
</body>
</html>
