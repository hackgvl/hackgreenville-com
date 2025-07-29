<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid max-w-full px-4 flex items-center justify-between">
        <a class="navbar-brand" href="{{ route('home') }}">
            @include('includes.logo')
        </a>
        <button class="navbar-toggler lg:hidden" type="button" onclick="toggleMenu()"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-collapse lg:bg-transparent bg-primary" id="navbarSupportedContent">
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
                
                <!-- Mobile only Slack links -->
                <li class="nav-item block lg:hidden border-t border-gray-600 mt-4 pt-4">
                    <a href="{{ route('join-slack') }}" class="block text-center border-2 border-gray-400 text-white hover:bg-gray-400 hover:text-primary px-4 py-2 rounded transition-colors mx-2">
                        <i class="fa fa-slack mr-1"></i>
                        Join Slack
                    </a>
                </li>
                <li class="nav-item block lg:hidden mt-3 mb-4">
                    <a href="https://hackgreenville.slack.com" class="block text-center px-4 py-2 rounded font-medium hover:opacity-90 transition-opacity mx-2" style="background-color: #60ae6d; color: #202020;" rel="noreferrer" target="_blank">
                        <i class="fa fa-slack mr-1"></i>
                        Log In to Slack
                    </a>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto hidden xl:flex">
                <li class="nav-item mr-2">
                    <a href="{{ route('join-slack') }}"
                       class="inline-block border-2 border-gray-400 text-white hover:bg-gray-400 hover:text-primary px-4 py-2 rounded transition-colors">
                        <i class="fa fa-slack mr-1"></i>
                        Join Slack
                    </a>
                </li>
                <li class="nav-item">
                    <a href="https://hackgreenville.slack.com"
                       class="inline-block px-4 py-2 rounded font-medium hover:opacity-90 transition-opacity"
                       style="background-color: #60ae6d; color: #202020;"
                       rel="noreferrer" target="_blank">
                        <i class="fa fa-slack mr-1"></i>
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