<!DOCTYPE html>
<html lang="de" data-theme="tafeldDark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'tafeld') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-base-100 text-base-content min-h-screen flex">
    <x-sidebar />

    <main class="flex-1 flex flex-col relative">
        <x-navbar :title="$title ?? ''" />
        <section class="p-6 flex-1 overflow-y-auto">
            <x-toast />
            {{ $slot }}
        </section>
        <footer class="footer footer-center p-4 bg-base-200 text-base-content/70">
            <p>© {{ date('Y') }} tafeld – Modular Dashboard</p>
        </footer>
    </main>

    @livewireScripts
</body>

</html>
