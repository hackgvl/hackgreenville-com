<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=2,shrink-to-fit=no">

    @unless(app()->environment('production'))
        <meta name="robots" content="noindex, nofollow">
    @endunless

    <meta name="description"
          content="@yield('description', 'HackGreenville exists to foster personal growth among the hackers of Greenville, SC and the surrounding area.')"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>

    <link rel="shortcut icon" href="{{asset('favicon.png')}}?1"/>

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{config('app.url')}}">
    <meta property="og:title" content="@yield('title', config('app.name'))">
    <meta property="og:description"
          content="@yield('description', 'HackGreenville exists to foster personal growth among the hackers of Greenville, SC and the surrounding area.')">
    <meta property="og:image" content="{{config('app.url')}}/img/hackgreenville-banner-preview.png">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{config('app.url')}}">
    <meta property="twitter:title" content="@yield('title', config('app.name'))">
    <meta property="twitter:description"
          content="@yield('description', 'HackGreenville exists to foster personal growth among the hackers of Greenville, SC and the surrounding area.')">
    <meta property="twitter:image" content="{{config('app.url')}}/img/hackgreenville-banner-preview.png">

    @yield('canonical')

    <!-- Preload Geist font for faster rendering -->
    <link rel="preload" as="font" type="font/woff2" crossorigin
          href="{{ Vite::asset('node_modules/@fontsource-variable/geist/files/geist-latin-wght-normal.woff2') }}">

    <!-- Styles (all fonts bundled in third-party.css via Vite) -->
    @vite(['resources/css/app.css', 'resources/css/third-party.css', 'resources/js/app.js'])

    @if(config('services.google.tagmanager.id'))
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async
                src="https://www.googletagmanager.com/gtag/js?id={{config('services.google.tagmanager.id')}}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());

            gtag('config', '{{config('services.google.tagmanager.id')}}');
        </script>
    @endif

    <script src='{{url('vendors/fullcalendar/packages/core/main.min.js')}}'></script>
    <script src='{{url('vendors/fullcalendar/packages/daygrid/main.js')}}'></script>
    <script src='{{url('vendors/fullcalendar/packages/list/main.js')}}'></script>

    @yield('head')
</head>
<body>
<div id="app">
    @include('layouts.top-nav')

    <div class="@if(!isset($show_loading)) hidden @endif fixed inset-0 bg-black/60 z-50 flex items-center justify-center" id="loading-overlay">
        <x-lucide-loader-circle class="w-12 h-12 text-white animate-spin"/>
    </div>

    <main class=" @if(isset($remove_space)) py-0 @else py-4 @endif ">
        @yield('content')
    </main>

    @include('layouts.footer')
</div>

@yield('js')

@stack('scripts')
</body>
</html>
