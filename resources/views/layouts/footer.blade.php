<footer class="bg-primary text-white pt-10 pb-6">
    <div class="max-w-6xl mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">

            {{-- Links --}}
            <div>
                <h5 class="text-sm font-semibold uppercase tracking-wider text-gray-300 mb-3">Navigate</h5>
                <ul class="list-none pl-0 space-y-1.5 text-sm">
                    <li><a href="{{route('calendar.index')}}" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5"><x-lucide-calendar class="w-3.5 h-3.5"/> Calendar</a></li>
                    <li><a href="{{route('events.index')}}" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5"><x-lucide-calendar-check class="w-3.5 h-3.5"/> Events</a></li>
                    <li><a href="{{route('orgs.index')}}" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5"><x-lucide-building class="w-3.5 h-3.5"/> Organizations</a></li>
                    <li><a href="{{route('labs.index')}}" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5"><x-lucide-flask-conical class="w-3.5 h-3.5"/> Labs</a></li>
                    <li><a href="{{route('hg-nights.index')}}" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5"><x-lucide-moon class="w-3.5 h-3.5"/> HG Nights</a></li>
                </ul>
            </div>

            {{-- Community --}}
            <div>
                <h5 class="text-sm font-semibold uppercase tracking-wider text-gray-300 mb-3">Community</h5>
                <ul class="list-none pl-0 space-y-1.5 text-sm">
                    <li><a href="{{route('about')}}" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5"><x-lucide-users class="w-3.5 h-3.5"/> About Us</a></li>
                    <li><a href="{{route('contribute')}}" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5"><x-lucide-handshake class="w-3.5 h-3.5"/> Contribute</a></li>
                    <li><a href="{{route('contact')}}" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5"><x-lucide-send class="w-3.5 h-3.5"/> Contact</a></li>
                    <li><a href="{{route('code-of-conduct')}}" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5"><x-lucide-check class="w-3.5 h-3.5"/> Code of Conduct</a></li>
                </ul>
            </div>

            {{-- Connect --}}
            <div>
                <h5 class="text-sm font-semibold uppercase tracking-wider text-gray-300 mb-3">Connect</h5>
                <ul class="list-none pl-0 space-y-1.5 text-sm">
                    <li><a href="/join-slack" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5"><x-lucide-slack class="w-3.5 h-3.5"/> Join Slack</a></li>
                    <li><a href="https://hackgreenville.slack.com" rel="noreferrer noopener" target="_blank" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5"><x-lucide-slack class="w-3.5 h-3.5"/> Log In to Slack</a></li>
                    <li><a href="https://www.meetup.com/hack-greenville/" rel="nofollow noopener" target="_blank" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5"><x-lucide-users-round class="w-3.5 h-3.5"/> Meetup</a></li>
                </ul>
            </div>

            {{-- Developers --}}
            <div>
                <h5 class="text-sm font-semibold uppercase tracking-wider text-gray-300 mb-3">Developers</h5>
                <ul class="list-none pl-0 space-y-1.5 text-sm">
                    <li><a href="https://github.com/hackgvl/hackgreenville-com" rel="noopener" target="_blank" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5"><x-lucide-github class="w-3.5 h-3.5"/> Join the Project</a></li>
                    <li><a href="/docs/api" rel="noopener" target="_blank" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5"><x-lucide-wrench class="w-3.5 h-3.5"/> Explore Our API</a></li>
                    <li>
                        <span class="text-gray-400 inline-flex items-center gap-1.5"><x-lucide-code class="w-3.5 h-3.5"/> Built with</span>
                        <a href="https://laravel.com" rel="nofollow noopener" target="_blank" class="text-gray-300 hover:text-white no-underline">Laravel</a>
                    </li>
                </ul>
            </div>

        </div>

        {{-- Copyright --}}
        <div class="border-t border-white/10 mt-8 pt-5 text-center text-xs text-gray-400">
            &copy; {{date('Y')}} <a href="http://hackgreenville.com" class="text-success hover:text-green-400 font-semibold no-underline">HackGreenville</a>.
            A program of <a href="https://refactorgvl.com/" rel="nofollow noopener" target="_blank" class="text-success hover:text-green-400 font-semibold no-underline">RefactorGVL</a> non-profit.
        </div>
    </div>
</footer>
