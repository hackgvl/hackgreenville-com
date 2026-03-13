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
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', config('app.name'))">
    <meta property="og:description"
          content="@yield('description', 'HackGreenville exists to foster personal growth among the hackers of Greenville, SC and the surrounding area.')">
    <meta property="og:image" content="@yield('og_image', config('app.url') . '/img/hackgreenville-banner-preview.png')">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', config('app.name'))">
    <meta property="twitter:description"
          content="@yield('description', 'HackGreenville exists to foster personal growth among the hackers of Greenville, SC and the surrounding area.')">
    <meta property="twitter:image" content="@yield('og_image', config('app.url') . '/img/hackgreenville-banner-preview.png')">

    <link rel="canonical" href="@yield('canonical', url()->current())" />

    <!-- Styles (all fonts bundled in third-party.css via Vite) -->
    @vite(['resources/css/app.css', 'resources/css/third-party.css', 'resources/js/app.js'])

    @if(config('services.google.tagmanager.id'))
        <link rel="preconnect" href="https://www.googletagmanager.com" crossorigin>
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

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@graph": [
            {
                "@type": "Organization",
                "name": "HackGreenville",
                "url": "{{ config('app.url') }}",
                "logo": "{{ config('app.url') }}/img/hackgreenville-banner-preview.png",
                "description": "HackGreenville is a community of hackers, makers, and tinkerers in the Greenville, SC area fostering personal growth through sharing and promoting local tech opportunities.",
                "address": {
                    "@type": "PostalAddress",
                    "addressLocality": "Greenville",
                    "addressRegion": "SC",
                    "addressCountry": "US"
                },
                "sameAs": [
                    "https://hackgreenville.slack.com",
                    "https://www.meetup.com/hack-greenville/",
                    "https://github.com/hackgvl"
                ]
            },
            {
                "@type": "WebSite",
                "name": "HackGreenville",
                "url": "{{ config('app.url') }}"
            }
        ]
    }
    </script>

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

@if(app()->environment('local'))
    <div class="fixed top-0 left-0 z-50 px-2 py-0.5 rounded text-[10px] font-mono text-white/80 bg-orange-600/70 backdrop-blur-sm pointer-events-none">
        {{ trim(exec('git branch --show-current')) }}
    </div>
@endif
</body>
</html>
