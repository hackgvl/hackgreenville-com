<nav class="navbar navbar-expand-md navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img class="navbar-brand-img" alt="PHP" src="{{asset('img/logo.png')}}" />
            {{ config('app.name', 'HackGreenville') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li>
                    <a class="nav-link @if(Route::is('calendar.*')) active @endif" href="{{ route('calendar.index') }}">
                        <i class="d-md-none d-lg-inline-block fa fa-calendar"></i>
                        {{ __('Calendar') }}
                    </a>
                </li>
                <li>
                    <a class="nav-link @if(Route::is('events.*')) active @endif" href="{{ route('events.index') }}">
                        <i class="d-md-none d-lg-inline-block fa fa-calendar-check-o"></i>
                        {{ __('Events') }}
                    </a>
                </li>
                <li>
                    <a class="nav-link @if(Route::is('orgs.*')) active @endif" href="{{ route('orgs.index') }}">
                        <i class="d-md-none d-lg-inline-block fa fa-users"></i>
                        {{ __('Organizations') }}
                    </a>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li>
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="d-md-none d-lg-inline-block fa fa-sign-in"></i>
                            {{ __('Login') }}</a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="d-md-none d-lg-inline-block fa fa-cloud"></i>
                            {{ __('Register') }}</a>
                    </li>
                @else
                    <li>
                        <a href="{{route('authed.carousel.index')}}" class="nav-link">Carousel</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="d-md-none d-lg-inline-block fa fa-sign-out"></i>
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
