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
                <x-nav-link route="events.index">Events</x-nav-link>
                <x-nav-link route="calendar.index">Calendar</x-nav-link>
                <x-nav-link route="orgs.index">Organizations</x-nav-link>
                <x-nav-link route="labs.index">Labs</x-nav-link>
                <x-nav-link route="hg-nights.index">HG Nights</x-nav-link>
                <x-nav-link route="about">About</x-nav-link>
                <x-nav-link route="contribute">Contribute</x-nav-link>
                <x-nav-link route="contact">Contact</x-nav-link>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="tw-navbar-nav tw-ml-auto">
                <li class="tw-nav-item">
                    <a href="{{ route('join-slack') }}"
                       class="tw-nav-link tw-btn tw-btn-outline-success tw-ml-2">
                        Join Slack
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
