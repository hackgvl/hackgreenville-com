<nav class="navbar navbar-expand-md navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img class="navbar-brand-img" alt="{{ config('app.name', 'HackGreenville') }}"
                 src="{{ asset('img/logo-v2.png') }}"/>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <x-nav-link route="calendar.index" icon="fa-calendar">{{ __('Calendar') }}</x-nav-link>
                <x-nav-link route="events.index" icon="fa-calendar-check-o">{{ __('Events') }}</x-nav-link>
                <x-nav-link route="orgs.index" icon="fa-building">{{ __('Organizations') }}</x-nav-link>
                <x-nav-link route="labs.index" icon="fa-flask">{{ __('Labs') }}</x-nav-link>
                <x-nav-link route="hg-nights.index" icon="fa-moon-o">{{ __('HG Nights') }}</x-nav-link>
                <x-nav-link route="about" icon="fa-users">{{ __('About Us') }}</x-nav-link>
                <x-nav-link route="give" icon="fa-handshake-o">{{ __('Give') }}</x-nav-link>
                <x-nav-link route="contact" icon="fa-paper-plane">{{ __('Contact') }}</x-nav-link>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <x-nav-link route="join-slack" icon="fa-slack"
                            class="btn btn-outline-secondary">{{ __('Join Slack') }}</x-nav-link>
                <li class="nav-item">
                    <a href="https://hackgreenville.slack.com"
                       class="nav-link btn btn-outline-success active ml-2" style="color: #202020;"
                       rel="noreferrer" target="_blank">
                        <i class="d-md-none d-lg-inline-block fa fa-slack"></i>
                        {{ __('Log In to Slack') }}
                    </a>
                </li>

                @if(request('testing-123') == 'working')
                    <!-- Authentication Links -->
                    @guest
                        <x-nav-link route="login" icon="fa-sign-in">{{ __('Login') }}</x-nav-link>
                        <x-nav-link route="register" icon="fa-cloud">{{ __('Register') }}</x-nav-link>
                    @else
                        {{-- Add condition here to check if the user has the role necessary to see this dropdown--}}
                        <li class="nav-item dropdown">
                            <a id="navbarDropdownAdmin" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <i class="fa fa-adjust"></i>
                                {{__('Admin Stuff')}} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdownAdmin">
                                <a href="#" class="dropdown-item">
                                    Placeholder
                                </a>
                            </div>
                        </li>
                        <li class="d-none d-md-inline-block">
                            <div class="vertical-divider"></div>
                        </li>
                        {{-- End condition--}}


                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="d-md-none d-lg-inline-block fa fa-sign-out"></i>
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                @endif
            </ul>
        </div>
    </div>
</nav>
