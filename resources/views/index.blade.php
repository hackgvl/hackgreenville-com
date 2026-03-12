@extends('layouts.app', ['remove_space' => true])

@section('title', 'Hackgreenville - A Developer Community in the Greenville SC Area')

@section('content')
    <div id="homepage" class="overflow-x-hidden">
        {{-- Hero --}}
        <div id="homepage-jumbotron" class="w-full text-white bg-gray-900 relative bg-cover bg-center" style="background-image: url('{{ asset('img/hackgreenville-banner.jpg') }}');">
            <div class="overlay"></div>
            <div class="max-w-7xl mx-auto py-16 sm:py-24 text-center px-4 relative z-10">
                <h1 class="text-3xl sm:text-5xl md:text-6xl font-light text-white drop-shadow-lg">Build Stuff. Meet People. Do cool things.</h1>
                <p class="lead my-8 sm:my-12 text-gray-100">Meetups &middot; Talks &middot; Projects</p>
                <a class="inline-block bg-success text-white px-6 sm:px-8 py-3 sm:py-4 rounded-lg text-lg sm:text-xl font-medium no-underline hover:opacity-90 transition-opacity drop-shadow-md" href="/join-slack" role="button">Request to Join Slack</a>
            </div>
        </div>

        {{-- What is HackGreenville --}}
        <div class="max-w-3xl mx-auto px-4 py-10 sm:py-12 text-center">
            <h2 class="text-2xl sm:text-3xl font-light mb-4">What is HackGreenville?</h2>
            <p class="text-lg text-gray-700 leading-relaxed">
                HackGreenville is a community of "hackers" located in and around Greenville, SC. Our
                community exists to foster personal growth for community
                members through sharing and promoting local tech opportunities.
            </p>
        </div>

        {{-- Join Us + meetup image --}}
        <div class="bg-gray-50 py-16">
            <div class="max-w-6xl mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div class="text-center">
                        <img src="{{url('img/meetup.jpeg')}}" alt="Join Us" class="max-w-full h-auto rounded-lg shadow-md">
                    </div>
                    <div>
                        <p class="summary text-gray-700 leading-relaxed">
                            HG is the <code class="bg-gray-200 px-1.5 py-0.5 rounded text-sm">"GO TO"</code> resource for discovering and connecting with Upstate SC
                            tech hackers, makers, and tinkerers.
                        </p>
                        <p class="summary mt-4 text-gray-700 leading-relaxed">
                            Explore the site for more meetups and events, and make sure to join our active <a
                                href="/join-slack" class="text-primary hover:text-blue-600 underline">Slack community</a> to connect further!
                        </p>
                        <a href="/join-slack" class="inline-block border-2 border-primary text-primary px-8 py-3 rounded-lg text-lg font-medium mt-6 no-underline hover:bg-primary hover:text-white transition-colors">
                            Join Us
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Upcoming Events --}}
        <div class="max-w-4xl mx-auto px-4 py-16 sm:py-20">
            <h3 class="text-center text-3xl font-semibold mb-10">
                Upcoming Events
            </h3>
            @if ($upcoming_events->isEmpty())
                <p class="text-center text-gray-500">
                    <strong class="font-bold">No</strong> events to display.
                </p>
            @else
                <div class="divide-y divide-gray-200">
                    @foreach ($upcoming_events as $event)
                        <div class="flex items-center justify-between gap-4 py-3 px-2">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <a href="{{ $event->url }}" rel="external" class="font-semibold text-base text-gray-900 hover:text-primary truncate block">
                                        {{ $event->event_name }}
                                    </a>
                                    @if($event->cancelled_at)
                                        <span class="text-danger text-xs font-bold">[CANCELLED]</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500">
                                    <a href="{{ route('orgs.show', $event->organization) }}" class="text-gray-500 hover:text-primary">{{ $event->organization->title }}</a>
                                </p>
                                <p class="text-sm text-gray-400">
                                    <x-lucide-calendar class="w-3.5 h-3.5 inline"/> {{ $event->active_at->format('F j') }}, {{ $event->active_at->format('i') === '00' ? $event->active_at->format('ga') : $event->active_at->format('g:ia') }}
                                </p>
                            </div>
                            @if(!$event->cancelled_at)
                                <a href="{{ $event->url }}" rel="external" class="shrink-0 text-sm bg-gray-100 text-gray-700 px-3 py-1.5 rounded hover:bg-gray-200 transition-colors no-underline">
                                    View Event
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Contribute --}}
        <div class="bg-gray-50 py-16">
            <div class="max-w-3xl mx-auto px-4 text-center">
                <h2 class="text-3xl font-light mb-4">Contribute</h2>
                <p class="summary text-gray-700 mb-8">
                    hackgreenville.com is built on the
                    <a href="https://laravel.com/" class="text-primary hover:text-blue-600 underline">Laravel</a> PHP framework
                </p>
                <a href="https://github.com/hackgvl/hackgreenville-com"
                   class="inline-flex items-center gap-3 no-underline text-gray-900 hover:text-primary transition-colors text-xl">
                    <x-lucide-github class="w-12 h-12"/>
                    <span class="font-medium">Join the Project</span>
                </a>
            </div>
        </div>
    </div>
@endsection
