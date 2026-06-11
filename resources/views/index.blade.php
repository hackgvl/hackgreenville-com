@extends('layouts.app', ['remove_space' => true])

@section('title', 'Hackgreenville - A Developer Community in the Greenville SC Area')
@section('description', 'Discover tech meetups, events, and organizations in Greenville, SC. Connect with local hackers, makers, and tinkerers through our Slack community and calendar of upcoming events.')

@section('content')
    <div id="homepage" class="overflow-x-hidden">
        {{-- Hero --}}
        <x-hero id="homepage-jumbotron" :image="asset('img/hackgreenville-banner.jpg')" eyebrow="Greenville, SC Tech Community">
            Build Stuff.<br class="hidden sm:block"/>
            Meet People.<br class="hidden sm:block"/>
            Do Cool Things.

            <x-slot:subtitle>
                Join hundreds of local hackers, makers, and tinkerers sharing meetups, talks, and projects.
            </x-slot:subtitle>

            <x-slot:actions>
                <x-button href="/join-slack">Join Our Slack</x-button>
                <x-button href="{{ route('events.index') }}" variant="ghost">Browse Events</x-button>
            </x-slot:actions>
        </x-hero>

        {{-- About + Image --}}
        <div class="py-16 sm:py-20">
            <div class="max-w-6xl mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14 items-center">
                    <div class="text-center lg:text-left">
                        <img src="{{ url('img/meetup.jpeg') }}" alt="HackGreenville community meetup" class="max-w-full h-auto rounded-lg shadow-md" loading="lazy">
                    </div>
                    <div>
                        <x-section-heading class="mb-4">What is HackGreenville?</x-section-heading>
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
                <x-section-heading class="mb-8">
                    Upcoming Events
                    <x-slot:actions>
                        <a href="{{ route('events.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-success hover:text-green-700 no-underline transition-colors">
                            View all events
                            <x-lucide-arrow-right aria-hidden="true" class="w-3.5 h-3.5"/>
                        </a>
                    </x-slot:actions>
                </x-section-heading>
                @if ($upcoming_events->isEmpty())
                    <p class="text-gray-500">
                        <strong class="font-bold">No</strong> events to display.
                    </p>
                @else
                    <div class="bg-white rounded-xl border border-gray-200 shadow-xs overflow-hidden divide-y divide-gray-100">
                        @foreach ($upcoming_events as $event)
                            @include('events._item', ['event' => $event])
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Get Involved --}}
        <div class="py-16 sm:py-20">
            <div class="max-w-6xl mx-auto px-4">
                <x-cta title="Get Involved">
                    HackGreenville is open source and community-driven. Contribute code, suggest features, or help improve the platform.

                    <x-slot:actions>
                        <x-button href="https://github.com/hackgvl/hackgreenville-com" rel="noopener" variant="outline">
                            <x-lucide-github aria-hidden="true" class="w-5 h-5"/>
                            View on GitHub
                        </x-button>
                        <x-button href="{{ route('contribute') }}" variant="outline">
                            <x-lucide-handshake aria-hidden="true" class="w-5 h-5"/>
                            Volunteer &amp; Sponsor
                        </x-button>
                    </x-slot:actions>
                </x-cta>
            </div>
        </div>
    </div>
@endsection
