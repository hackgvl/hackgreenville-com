<nav class="tw-navbar tw-navbar-expand-md tw-navbar-dark tw-bg-primary" style="position: relative;">
    <div class="tw-container-fluid">
        <a class="tw-navbar-brand" href="{{ route('home') }}">
            @include('includes.logo')
        </a>
        <button class="tw-navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="tw-navbar-toggler-icon"></span>
        </button>

        <div class="tw-navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="tw-navbar-nav tw-mr-auto">
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
            <ul class="tw-navbar-nav tw-ml-auto">
                <x-nav-link route="join-slack" icon="fa-slack"
                            class="tw-btn tw-btn-outline-secondary">Join Slack</x-nav-link>
                <li class="tw-nav-item">
                    <a href="https://hackgreenville.slack.com"
                       class="tw-nav-link tw-btn tw-btn-outline-success tw-active tw-ml-2"
                       rel="noreferrer" target="_blank">
                        <i class="tw-d-md-none tw-d-lg-inline-block fa fa-slack"></i>
                        Log In to Slack
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
