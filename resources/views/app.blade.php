<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title inertia>{{ config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">

    <!-- Meta Tags -->
    <meta name="description" content="HackGreenville exists to foster personal growth among the hackers of Greenville, SC and the surrounding area.">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ config('app.url') }}">
    <meta property="og:title" content="{{ config('app.name') }}">
    <meta property="og:description" content="HackGreenville exists to foster personal growth among the hackers of Greenville, SC and the surrounding area.">
    <meta property="og:image" content="{{ config('app.url') }}/img/hackgreenville-banner-preview.png">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ config('app.url') }}">
    <meta property="twitter:title" content="{{ config('app.name') }}">
    <meta property="twitter:description" content="HackGreenville exists to foster personal growth among the hackers of Greenville, SC and the surrounding area.">
    <meta property="twitter:image" content="{{ config('app.url') }}/img/hackgreenville-banner-preview.png">

    @if(config('services.google.tagmanager.id'))
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google.tagmanager.id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ config('services.google.tagmanager.id') }}');
        </script>
    @endif

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])
    @inertiaHead
</head>
<body class="font-sans antialiased">
    @inertia
</body>
</html>
