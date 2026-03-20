<nav class="bg-primary" id="main-nav" aria-label="Main">
    <div class="relative flex flex-wrap items-center justify-between px-4 py-2 max-w-6xl mx-auto">
    <a class="inline-block py-1 mr-4 no-underline" href="{{ route('home') }}">
        @include('includes.logo')
    </a>

    <input type="checkbox" id="nav-toggle" class="hidden peer" aria-hidden="true"/>
    <label for="nav-toggle" class="nav-break:hidden px-3 py-1 rounded border border-white/10 cursor-pointer"
           aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
        <x-lucide-menu aria-hidden="true" class="w-6 h-6 text-white/70"/>
    </label>

    <div class="hidden peer-checked:block nav-break:flex nav-break:grow nav-break:items-center nav-break:justify-between nav-break:relative nav-break:bg-transparent nav-break:shadow-none nav-break:p-0 nav-break:w-auto absolute top-full left-0 right-0 z-50 bg-primary p-4 shadow-lg w-full"
         id="navMenu" role="navigation">
        <ul class="flex flex-col nav-break:flex-row nav-break:items-center list-none pl-0 mb-0 mr-auto divide-y divide-white/10 nav-break:divide-y-0">

            {{-- Events dropdown (desktop) --}}
            <li class="hidden nav-break:block relative group">
                <a href="{{ route('events.index') }}"
                   aria-haspopup="true"
                   class="flex items-center gap-1 py-2 px-2 text-sm font-medium no-underline transition-colors {{ Route::is('events.index') || Route::is('calendar.index') ? 'text-white font-semibold' : 'text-white/70 hover:text-white' }}">
                    Events
                    <x-lucide-chevron-down aria-hidden="true" class="w-3 h-3"/>
                </a>
                <div class="invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all duration-150 absolute left-0 top-full pt-1 z-50">
                    <ul class="bg-white rounded-lg shadow-lg border border-gray-200 py-1 min-w-[10rem] list-none pl-0 mb-0">
                        <li>
                            <a href="{{ route('events.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary no-underline transition-colors">
                                Event List
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('calendar.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary no-underline transition-colors">
                                Calendar View
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Mobile: events links flat --}}
            <x-nav-link route="events.index" class="nav-break:hidden">Events</x-nav-link>
            <x-nav-link route="calendar.index" class="nav-break:hidden">Calendar</x-nav-link>

            <x-nav-link route="orgs.index">Organizations</x-nav-link>
            <x-nav-link route="labs.index">Labs</x-nav-link>
            <x-nav-link route="hg-nights.index">HG Nights</x-nav-link>
            <x-nav-link route="about">About</x-nav-link>

            {{-- More dropdown (desktop) --}}
            <li class="hidden nav-break:block relative group">
                <button aria-haspopup="true" class="flex items-center gap-1 py-2 px-2 text-sm font-medium no-underline transition-colors text-white/70 hover:text-white">
                    More
                    <x-lucide-chevron-down aria-hidden="true" class="w-3 h-3"/>
                </button>
                <div class="invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all duration-150 absolute right-0 top-full pt-1 z-50">
                    <ul class="bg-white rounded-lg shadow-lg border border-gray-200 py-1 min-w-[10rem] list-none pl-0 mb-0">
                        <li>
                            <a href="https://hackgvl.github.io/open-map-data-multi-layers-demo/" target="_blank" rel="noopener" class="flex items-center gap-1 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary no-underline transition-colors">
                                Map
                                <x-lucide-external-link class="w-3 h-3"/>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('map-layers.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary no-underline transition-colors">
                                Map Layers
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('contribute') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary no-underline transition-colors">
                                Contribute
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('contact') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary no-underline transition-colors">
                                Contact
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Mobile: more links flat --}}
            <li class="nav-break:hidden">
                <a href="https://hackgvl.github.io/open-map-data-multi-layers-demo/" target="_blank" rel="noopener"
                   class="flex items-center gap-1 py-2 px-2 text-sm font-medium no-underline transition-colors text-white/70 hover:text-white">
                    Map
                    <x-lucide-external-link class="w-3 h-3"/>
                </a>
            </li>
            <x-nav-link route="map-layers.index" class="nav-break:hidden">Map Layers</x-nav-link>
            <x-nav-link route="contribute" class="nav-break:hidden">Contribute</x-nav-link>
            <x-nav-link route="contact" class="nav-break:hidden">Contact</x-nav-link>
        </ul>

        <div class="mt-2 pt-2 border-t border-white/20 nav-break:mt-0 nav-break:pt-0 nav-break:border-0 nav-break:ml-4">
            <a href="{{ route('join-slack') }}"
               class="block nav-break:inline-block text-center text-white text-sm font-semibold no-underline bg-success hover:bg-green-700 rounded-full px-5 py-2 transition-colors">
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
