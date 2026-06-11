<nav class="bg-primary sticky top-0 z-40" id="main-nav" aria-label="Main">
    <div class="relative flex items-center justify-between gap-3 nav-break:gap-6 px-4 sm:px-6 py-2.5 max-w-6xl mx-auto">
    <a class="shrink-0 py-1 no-underline [&_img]:h-12 nav-break:[&_img]:h-14" href="{{ route('home') }}" aria-label="Homepage">
        @include('includes.logo')
    </a>

    <input type="checkbox" id="nav-toggle" class="hidden peer" aria-hidden="true"/>
    <label for="nav-toggle"
           class="relative nav-break:hidden p-2.5 rounded-lg text-white/80 hover:text-white hover:bg-white/10 cursor-pointer peer-checked:[&_[data-icon=menu]]:hidden peer-checked:[&_[data-icon=close]]:block"
           aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="absolute top-1/2 left-1/2 size-[max(100%,3rem)] -translate-1/2 pointer-fine:hidden" aria-hidden="true"></span>
        <x-lucide-menu data-icon="menu" aria-hidden="true" class="size-6"/>
        <x-lucide-x data-icon="close" aria-hidden="true" class="hidden size-6"/>
    </label>

    <div class="hidden peer-checked:block nav-break:flex nav-break:grow nav-break:items-center nav-break:justify-between nav-break:relative nav-break:bg-transparent nav-break:border-0 nav-break:shadow-none nav-break:p-0 absolute top-full inset-x-0 z-50 bg-primary border-t border-white/10 p-3 shadow-lg"
         id="navMenu" role="navigation">
        <ul role="list" class="flex flex-col gap-y-1 nav-break:flex-row nav-break:items-center nav-break:gap-x-1.5 list-none pl-0 mb-0 mr-auto">

            {{-- Mobile: events links flat --}}
            <x-nav-link route="events.index" class="nav-break:hidden">Events</x-nav-link>
            <x-nav-link route="calendar.index" class="nav-break:hidden">Calendar</x-nav-link>

            {{-- Events dropdown (desktop) --}}
            <x-nav-dropdown label="Events" :href="route('events.index')" :active="Route::is('events.index') || Route::is('calendar.index')">
                <x-nav-dropdown-link route="events.index">Event List</x-nav-dropdown-link>
                <x-nav-dropdown-link route="calendar.index">Calendar View</x-nav-dropdown-link>
            </x-nav-dropdown>

            <x-nav-link route="orgs.index">Organizations</x-nav-link>
            <x-nav-link route="labs.index">Labs</x-nav-link>
            <x-nav-link route="hg-nights.index">HG Nights</x-nav-link>
            <x-nav-link route="about">About</x-nav-link>

            {{-- More dropdown (desktop) --}}
            <x-nav-dropdown label="More" align="right">
                <x-nav-dropdown-link route="contribute">Contribute</x-nav-dropdown-link>
                <x-nav-dropdown-link route="contact">Contact</x-nav-dropdown-link>
            </x-nav-dropdown>

            {{-- Mobile: more links flat --}}
            <x-nav-link route="contribute" class="nav-break:hidden">Contribute</x-nav-link>
            <x-nav-link route="contact" class="nav-break:hidden">Contact</x-nav-link>
        </ul>

        <div class="mt-3 nav-break:mt-0 nav-break:ml-4">
            <a href="{{ route('join-slack') }}"
               class="block nav-break:inline-block text-center text-white text-base nav-break:text-sm font-semibold no-underline bg-success hover:bg-green-600 rounded-lg px-4 py-2.5 nav-break:py-2 transition-colors">
                Join Slack
            </a>
        </div>
    </div>
    </div>
</nav>

<script>
document.getElementById('nav-toggle').addEventListener('change', function() {
    document.querySelector('label[for="nav-toggle"]').setAttribute('aria-expanded', this.checked ? 'true' : 'false');
});
</script>
