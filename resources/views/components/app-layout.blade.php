<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>Kundenerfassung</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-950 text-gray-100">
    <div class="max-w-5xl mx-auto py-8 px-6">
        <x-customers.alerts />
        <x-customers.form :action="route('customers.store')" method="POST" />
    </div>
</body>

</html>
