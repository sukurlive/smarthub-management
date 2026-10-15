<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Smart-Hub Management') }}</title>

    <!-- Premium Google Fonts: Outfit & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS styles and Inertia scripts -->
    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Outfit', system-ui, -apple-system, sans-serif;
        }
    </style>
    @vite(['resources/js/app.js'])
    @inertiaHead
</head>
<body class="font-sans antialiased bg-slate-950 text-slate-100 min-h-screen selection:bg-teal-400 selection:text-slate-950">
    @inertia
</body>
</html>
