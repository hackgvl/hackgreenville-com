<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=2,shrink-to-fit=no">

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

    <!-- Styles -->
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

    @if($__env->yieldContent('breadcrumbs'))
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb flex flex-wrap list-none px-4 py-2 mb-4 bg-gray-100 rounded">
                @yield('breadcrumbs')
            </ol>
        </nav>
    @endif

    <div class="loading @if(!isset($show_loading)) hidden @endif fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center">
        <i class="fa fa-spin fa-spinner text-5xl text-white"></i>
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
