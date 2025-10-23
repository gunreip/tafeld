<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'tafeld')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<header class="p-4 bg-gray-200"><h1 class="text-xl">tafeld</h1></header>
<main class="p-6">@yield('content')</main>
</body>
</html>