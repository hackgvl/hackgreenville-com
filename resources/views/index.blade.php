@extends('layouts.app', ['remove_space' => true])

@section('title', 'Hackgreenville - A Developer Community in the Greenville SC Area')

@section('content')
    <div id="homepage">
        <div id="homepage-jumbotron" class="jumbotron jumbotron-fluid text-white bg-gray-900 relative">
            <div class="overlay"></div>
            <div class="container max-w-7xl mx-auto py-20 text-center px-4">
                <h1 class="display-4">Build Stuff. Meet People. Do cool things.</h1>
                <p class="lead my-12">Meetups &middot; Talks &middot; Projects</p>
                <p class="lead">
                    <a class="btn btn-success text-gray-800 btn-lg no-underline" href="/join-slack" role="button">Request to Join
                        Slack</a>
                </p>
            </div>
        </div>

        <div class="container max-w-7xl mx-auto my-12 px-4">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <div class="md:col-span-6 lg:col-span-8">
                    <div class="text-center">
                        <h3 class="screaming-hackgreenville-question">What is HackGreenville?</h3>

                        <p class="summary">
                            HackGreenville is a community of "hackers" located in and around Greenville, SC. Our
                            community exists to foster personal growth for community
                            members through sharing and promoting local tech opportunities.
                        </p>
                    </div>

                    <hr class="sm:hidden my-8">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mt-12 items-center">
                        <div class="text-center">
                            <img src="{{url('img/meetup.jpeg')}}" alt="Join Us" class="max-w-full h-auto">
                        </div>
                        <div>
                            <p class="summary">
                                HG is the <code class="bg-gray-100 px-1 py-0.5 rounded">"GO TO"</code> resource for discovering and connecting with Upstate SC
                                tech hackers, makers, and tinkerers.
                            </p>
                            <p class="summary">
                                Explore the site for more meetups and events, and make sure to join our active <a
                                    href="/join-slack" class="text-primary hover:text-blue-600 underline">Slack community</a> to connect further!
                            </p>
                            <button onclick="location.href='/join-slack'" class="btn btn-outline-primary btn-lg mt-4">
                                Join Us
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mt-12">
                        <div class="my-12">
                            <h2 class="display-5 text-center mt-12">Contribute</h2>
                            <div class="summary container max-w-7xl mx-auto px-4">
                                hackgreenville.com is built on the
                                <a href="https://laravel.com/" class="text-primary hover:text-blue-600 underline">Laravel</a> PHP framework
                            </div>
                        </div>
                        <div class="text-center">
                            <a href="https://github.com/hackgvl/hackgreenville-com"
                               class="no-underline text-gray-900 hover:text-gray-700">
                                <p style="line-height:1;" class="mt-12 text-8xl sm:text-9xl md:text-[15em]">
                                    <i class="fa fa-github"></i>
                                </p>
                                <p class="summary">Join the Project</p>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-6 lg:col-span-4 mt-5 md:mt-3">
                    <div>
                        <h3 class="text-center text-2xl font-semibold mb-6">
                            Upcoming Events
                        </h3>
                        <ul
                            @class(['timeline'=>$upcoming_events->isNotEmpty()])
                        >
                            @if ($upcoming_events->isEmpty())
                                <li>
                                    <strong class="font-bold">No</strong> events to display.
                                </li>
                            @else
                                @foreach ($upcoming_events as $event)
                                    <li class="timeline-inverted">
                                        <div class="timeline-badge bg-success">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <h4 class="timeline-title text-lg font-semibold">
                                                    @if($event->cancelled_at)
                                                        <div class="text-danger">
                                                            [CANCELLED]
                                                        </div>
                                                    @endif
                                                    {{ $event->event_name }}
                                                </h4>
                                                <p class="timeline-subtitle h6 text-base">
                                                    {{ $event->organization->title }}
                                                </p>
                                                <p>
                                                    <small class="text-gray-500 text-sm">
                                                        <i class="fa fa-calendar"></i> {{ $event->active_at->format('M/d h:i A') }}
                                                    </small>
                                                </p>
                                            </div>
                                            <div class="timeline-body">
                                                <div>
                                                    <button
                                                        onClick="showMoreTimeline(@js(['title' => $event->event_name, 'html' => str($event->description)->markdown()->toString(), 'confirmButtonText' => "Close"]))"
                                                        class="btn btn-secondary"
                                                        type="button"
                                                    >
                                                        Details
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection