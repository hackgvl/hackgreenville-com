<nav class="navbar navbar-expand-md navbar-dark bg-primary">
    <div class="container-fluid max-w-full px-4">
        <a class="navbar-brand" href="{{ route('home') }}">
            @include('includes.logo')
        </a>
        <button class="navbar-toggler md:hidden" type="button" onclick="toggleMenu()"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-collapse" id="navbarSupportedContent">
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
            <ul class="navbar-nav ml-auto md:ml-auto">
                <x-nav-link route="join-slack" icon="fa-slack"
                            class="btn btn-outline-secondary">Join Slack</x-nav-link>
                <li class="nav-item">
                    <a href="https://hackgreenville.slack.com"
                       class="nav-link btn btn-outline-success active ml-0 md:ml-2" style="color: #202020;"
                       rel="noreferrer" target="_blank">
                        <i class="hidden md:hidden lg:inline-block fa fa-slack"></i>
                        Log In to Slack
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
function toggleMenu() {
    const menu = document.getElementById('navbarSupportedContent');
    menu.classList.toggle('show');
}
</script>