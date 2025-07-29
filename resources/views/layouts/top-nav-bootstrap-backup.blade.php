<nav class="navbar navbar-expand-md navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">
            @include('includes.logo')
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <x-nav-link route="calendar.index" icon="fa-calendar">Calendar</x-nav-link>
                <x-nav-link route="events.index" icon="fa-calendar-check-o">Events</x-nav-link>
                <x-nav-link route="orgs.index" icon="fa-building">Organizations</x-nav-link>
                <x-nav-link route="labs.index" icon="fa-flask">Labs</x-nav-link>
                <x-nav-link route="hg-nights.index" icon="fa-moon-o">HG Nights</x-nav-link>
                <x-nav-link route="about" icon="fa-users">About Us</x-nav-link>
                <x-nav-link route="contribute" icon="fa-handshake-o">Contribute</x-nav-link>
                <x-nav-link route="contact" icon="fa-paper-plane">Contact</x-nav-link>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <x-nav-link route="join-slack" icon="fa-slack"
                            class="btn btn-outline-secondary">Join Slack</x-nav-link>
                <li class="nav-item">
                    <a href="https://hackgreenville.slack.com"
                       class="nav-link btn btn-outline-success active ml-2" style="color: #202020;"
                       rel="noreferrer" target="_blank">
                        <i class="d-md-none d-lg-inline-block fa fa-slack"></i>
                        Log In to Slack
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
