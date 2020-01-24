<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', config('app.name'))
    </title>

    <link rel="shortcut icon" href="{{asset('favicon.png')}}?1"/>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet"/>
    <!-- Fonts and icons -->

    <!-- CSS Files -->
    <link href="//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>


    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css"/>
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <link href='{{url('vendors/fullcalendar/packages/core/main.min.css')}}' rel='stylesheet'/>
    <link href='{{url('vendors/fullcalendar/packages/daygrid/main.min.css')}}' rel='stylesheet'/>

    <script src='{{url('vendors/fullcalendar/packages/core/main.min.js')}}'></script>
    <script src='{{url('vendors/fullcalendar/packages/daygrid/main.js')}}'></script>

@if(config('services.google.tagmanager.id'))
    <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{config('services.google.tagmanager.id')}}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{{config('services.google.tagmanager.id')}}');
    </script>
    @endif

    @yield('head')
</head>
<body>
    <div id="app">
        @include('layouts.top-nav')

        @if($__env->yieldContent('breadcrumbs'))
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                @yield('breadcrumbs')
            </ol>
        </nav>
        @endif

        <main class=" @if(isset($remove_space)) py-0 @else py-4 @endif ">
            @yield('content')
        </main>

        @include('layouts.footer')
    </div>
    <script type="text/javascript" src="{{mix('js/app.js')}}"></script>
    @yield('js')
</body>
</html>
