@extends('layouts.app', ['remove_space' => true])

@section('title', 'Hackgreenville - A Developer Community in the Greenville SC Area')
@section('description', 'Discover tech meetups, events, and organizations in Greenville, SC. Connect with local hackers, makers, and tinkerers through our Slack community and calendar of upcoming events.')

@section('content')
    <div id="homepage" class="overflow-x-hidden">
        {{-- Hero --}}
        <div id="homepage-jumbotron" class="w-full text-white bg-gray-900 relative overflow-hidden min-h-[28rem] sm:min-h-[32rem] flex items-center">
            <img src="{{ asset('img/hackgreenville-banner.jpg') }}" alt="" class="absolute inset-0 w-full h-full object-cover object-center scale-105" aria-hidden="true"/>
            <div class="absolute inset-0 bg-gradient-to-r from-gray-900/85 via-gray-900/60 to-gray-900/40 z-[2]"></div>
            <div class="max-w-6xl mx-auto w-full px-6 sm:px-8 py-16 sm:py-24 relative z-10">
                <div class="max-w-2xl">
                    <p class="text-sm sm:text-base font-medium tracking-widest uppercase text-success mb-4 sm:mb-6">Greenville, SC Tech Community</p>
                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-white leading-tight">
                        Build Stuff.<br class="hidden sm:block"/>
                        Meet People.<br class="hidden sm:block"/>
                        Do Cool Things.
                    </h1>
                    <p class="text-lg sm:text-xl text-gray-300 mt-5 sm:mt-6 max-w-lg">
                        Join hundreds of local hackers, makers, and tinkerers sharing meetups, talks, and projects.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 mt-8 sm:mt-10">
                        <a class="inline-block bg-success text-white px-7 py-3.5 rounded-lg text-base font-semibold no-underline hover:bg-green-600 transition-colors" href="/join-slack">
                            Join Our Slack
                        </a>
                        <a class="inline-block bg-white/10 backdrop-blur text-white px-7 py-3.5 rounded-lg text-base font-semibold no-underline hover:bg-white/20 transition-colors border border-white/20" href="{{ route('events.index') }}">
                            Browse Events
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- About + Image --}}
        <div class="py-16 sm:py-20">
            <div class="max-w-6xl mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14 items-center">
                    <div class="text-center lg:text-left">
                        <img src="{{ url('img/meetup.jpeg') }}" alt="HackGreenville community meetup" class="max-w-full h-auto rounded-lg shadow-md" loading="lazy">
                    </div>
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-light mb-4">What is HackGreenville?</h2>
                        <p class="text-base text-gray-700 leading-relaxed mb-3">
                            HackGreenville is a community of "hackers" in and around Greenville, SC. We exist to foster personal growth through sharing and promoting local tech opportunities.
                        </p>
                        <p class="text-base text-gray-700 leading-relaxed">
                            We're the go-to resource for discovering and connecting with Upstate SC tech hackers, makers, and tinkerers. Explore the site for meetups and events, or join our active
                            <a href="/join-slack" class="text-primary hover:text-blue-600 underline">Slack community</a> to connect further.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Upcoming Events --}}
        <div class="bg-gray-50 py-16 sm:py-20">
            <div class="max-w-6xl mx-auto px-4">
                <h2 class="text-center text-2xl sm:text-3xl font-light mb-8">
                    Upcoming Events
                </h2>
                @if ($upcoming_events->isEmpty())
                    <p class="text-center text-gray-500">
                        <strong class="font-bold">No</strong> events to display.
                    </p>
                @else
                    <div class="bg-white rounded-lg shadow-xs overflow-hidden divide-y divide-gray-200">
                        @foreach ($upcoming_events as $event)
                            @include('events._item', ['event' => $event])
                        @endforeach
                    </div>
                    <div class="text-center mt-6">
                        <a href="{{ route('events.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-primary hover:text-blue-700 no-underline transition-colors">
                            View all events
                            <x-lucide-arrow-right aria-hidden="true" class="w-3.5 h-3.5"/>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Get Involved --}}
        <div class="py-16 sm:py-20">
            <div class="max-w-3xl mx-auto px-4 text-center">
                <h2 class="text-2xl sm:text-3xl font-light mb-3">Get Involved</h2>
                <p class="text-base text-gray-600 mb-8 max-w-xl mx-auto">
                    HackGreenville is open source and community-driven. Contribute code, suggest features, or help improve the platform.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="https://github.com/hackgvl/hackgreenville-com"
                       rel="noopener"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-lg border border-gray-200 text-gray-800 hover:border-gray-300 hover:bg-gray-50 transition-colors no-underline text-sm font-medium">
                        <x-lucide-github aria-hidden="true" class="w-5 h-5"/>
                        View on GitHub
                    </a>
                    <a href="{{ route('contribute') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-lg border border-gray-200 text-gray-800 hover:border-gray-300 hover:bg-gray-50 transition-colors no-underline text-sm font-medium">
                        <x-lucide-handshake aria-hidden="true" class="w-5 h-5"/>
                        Volunteer &amp; Sponsor
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
