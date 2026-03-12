<nav class="relative flex flex-wrap items-center justify-between px-4 py-2 bg-primary" id="main-nav">
    <a class="inline-block py-1 mr-4 no-underline" href="{{ route('home') }}">
        @include('includes.logo')
    </a>

    <input type="checkbox" id="nav-toggle" class="hidden peer" aria-hidden="true"/>
    <label for="nav-toggle" class="nav-break:hidden px-3 py-1 rounded border border-white/10 cursor-pointer"
           role="button" aria-controls="navMenu" aria-label="Toggle navigation">
        <x-lucide-menu class="w-6 h-6 text-white/70"/>
    </label>

    <div class="hidden peer-checked:block nav-break:flex nav-break:flex-grow nav-break:items-center nav-break:justify-between nav-break:relative nav-break:bg-transparent nav-break:shadow-none nav-break:p-0 nav-break:w-auto absolute top-full left-0 right-0 z-50 bg-primary p-4 shadow-lg w-full"
         id="navMenu">
        <ul class="flex flex-col nav-break:flex-row nav-break:items-center list-none pl-0 mb-0 mr-auto divide-y divide-white/10 nav-break:divide-y-0">
            <x-nav-link route="events.index">Events</x-nav-link>
            <x-nav-link route="calendar.index">Calendar</x-nav-link>
            <x-nav-link route="orgs.index">Organizations</x-nav-link>
            <x-nav-link route="labs.index">Labs</x-nav-link>
            <x-nav-link route="hg-nights.index">HG Nights</x-nav-link>
            <x-nav-link route="about">About</x-nav-link>
            <x-nav-link route="contribute">Contribute</x-nav-link>
            <x-nav-link route="contact">Contact</x-nav-link>
        </ul>

        <div class="mt-2 pt-2 border-t border-white/20 nav-break:mt-0 nav-break:pt-0 nav-break:border-0 nav-break:ml-4">
            <a href="{{ route('join-slack') }}"
               class="block nav-break:inline-block text-center text-white text-sm font-semibold no-underline bg-success hover:bg-green-700 rounded-full px-5 py-2 transition-colors">
                Join Slack
            </a>
        </div>
    </div>
</nav>
