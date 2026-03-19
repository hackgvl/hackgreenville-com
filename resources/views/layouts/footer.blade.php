<footer class="bg-primary text-white pt-12 pb-6" aria-label="Site footer">
    <div class="max-w-6xl mx-auto px-4">

        {{-- Tagline --}}
        <div class="mb-10">
            <a href="/" class="text-white no-underline hover:text-white">
                <span class="text-lg font-bold tracking-tight">HackGreenville</span>
            </a>
            <p class="text-gray-400 text-sm mt-1">Build stuff. Meet people. Do cool things.</p>
        </div>

        <nav class="grid grid-cols-2 md:grid-cols-4 gap-8" aria-label="Footer">

            {{-- Navigate --}}
            <div>
                <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3">Navigate</h2>
                <ul class="list-none pl-0 space-y-2 text-sm">
                    <li><a href="{{route('calendar.index')}}" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5 transition-colors"><x-lucide-calendar aria-hidden="true" class="w-3.5 h-3.5"/> Calendar</a></li>
                    <li><a href="{{route('events.index')}}" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5 transition-colors"><x-lucide-calendar-check aria-hidden="true" class="w-3.5 h-3.5"/> Events</a></li>
                    <li><a href="{{route('orgs.index')}}" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5 transition-colors"><x-lucide-building aria-hidden="true" class="w-3.5 h-3.5"/> Organizations</a></li>
                    <li><a href="{{route('labs.index')}}" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5 transition-colors"><x-lucide-flask-conical aria-hidden="true" class="w-3.5 h-3.5"/> Labs</a></li>
                    <li><a href="{{route('hg-nights.index')}}" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5 transition-colors"><x-lucide-moon aria-hidden="true" class="w-3.5 h-3.5"/> HG Nights</a></li>
                </ul>
            </div>

            {{-- Community --}}
            <div>
                <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3">Community</h2>
                <ul class="list-none pl-0 space-y-2 text-sm">
                    <li><a href="{{route('about')}}" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5 transition-colors"><x-lucide-users aria-hidden="true" class="w-3.5 h-3.5"/> About Us</a></li>
                    <li><a href="{{route('contribute')}}" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5 transition-colors"><x-lucide-handshake aria-hidden="true" class="w-3.5 h-3.5"/> Contribute</a></li>
                    <li><a href="{{route('contact')}}" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5 transition-colors"><x-lucide-send aria-hidden="true" class="w-3.5 h-3.5"/> Contact</a></li>
                    <li><a href="{{route('code-of-conduct')}}" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5 transition-colors"><x-lucide-shield-check aria-hidden="true" class="w-3.5 h-3.5"/> Code of Conduct</a></li>
                </ul>
            </div>

            {{-- Connect --}}
            <div>
                <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3">Connect</h2>
                <ul class="list-none pl-0 space-y-2 text-sm">
                    <li><a href="/join-slack" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5 transition-colors"><x-lucide-slack aria-hidden="true" class="w-3.5 h-3.5"/> Join Slack</a></li>
                    <li><a href="https://hackgreenville.slack.com" rel="noreferrer noopener" target="_blank" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5 transition-colors"><x-lucide-slack aria-hidden="true" class="w-3.5 h-3.5"/> Log In to Slack<span class="sr-only"> (opens in new tab)</span></a></li>
                    <li><a href="https://www.meetup.com/hack-greenville/" rel="nofollow noopener" target="_blank" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5 transition-colors"><x-lucide-users-round aria-hidden="true" class="w-3.5 h-3.5"/> Meetup<span class="sr-only"> (opens in new tab)</span></a></li>
                </ul>
            </div>

            {{-- Developers --}}
            <div>
                <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3">Developers</h2>
                <ul class="list-none pl-0 space-y-2 text-sm">
                    <li><a href="https://github.com/hackgvl/hackgreenville-com" rel="noopener" target="_blank" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5 transition-colors"><x-lucide-github aria-hidden="true" class="w-3.5 h-3.5"/> Join the Project<span class="sr-only"> (opens in new tab)</span></a></li>
                    <li><a href="/docs/api" rel="noopener" target="_blank" class="text-gray-300 hover:text-white no-underline inline-flex items-center gap-1.5 transition-colors"><x-lucide-wrench aria-hidden="true" class="w-3.5 h-3.5"/> Explore Our API<span class="sr-only"> (opens in new tab)</span></a></li>
                    <li>
                        <span class="text-gray-400 inline-flex items-center gap-1.5">
                            <x-lucide-code aria-hidden="true" class="w-3.5 h-3.5"/> Built with
                            <a href="https://laravel.com" rel="nofollow noopener" target="_blank" class="text-gray-300 hover:text-white no-underline transition-colors">Laravel<span class="sr-only"> (opens in new tab)</span></a>
                        </span>
                    </li>
                </ul>
            </div>

        </nav>

        {{-- Copyright --}}
        <div class="border-t border-white/10 mt-10 pt-5 text-center text-xs text-gray-400">
            &copy; {{date('Y')}} <a href="http://hackgreenville.com" class="text-success hover:text-green-400 font-semibold no-underline transition-colors">HackGreenville</a>.
            A program of <a href="https://refactorgvl.com/" rel="nofollow noopener" target="_blank" class="text-success hover:text-green-400 font-semibold no-underline transition-colors">RefactorGVL</a> non-profit.
        </div>
    </div>
</footer>
