<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>
    <meta name="description" content="PesonA Surakarta- koleksi foto, video, artikel dan acara sejarah Indonesia." />
    <meta name="keywords"
        content="galeri sejarah, sejarah Indonesia, pesona surakarta, foto sejarah, video sejarah, artikel sejarah, event sejarah" />
    <!-- Fonts -->


    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>
