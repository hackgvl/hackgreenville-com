@extends('layouts.app', ['remove_space' => true])

@section('title', 'Hackgreenville - A Developer Community in the Greenville SC Area')

@section('content')
    <div id="homepage">
        <div id="homepage-jumbotron" class="jumbotron jumbotron-fluid text-white bg-dark position-relative">
            <div class="overlay"></div>
            <div class="container py-5 text-center">
                <h1 class="display-4">Build Stuff. Meet People. Do cool things.</h1>
                <p class="lead my-5">Meetups &middot; Talks &middot; Projects</p>
                <p class="lead">
                    <a class="btn btn-success text-gray btn-lg" href="/join-slack" role="button">Request to Join
                        Slack</a>
                </p>
            </div>
        </div>

        <div class="container my-5">
            <div class="row">
                <div class="col-md-6 col-lg-8">
                    <div class="text-center">
                        <h3 class="screaming-hackgreenville-question">What is HackGreenville?</h3>

                        <p class="summary">
                            HackGreenville is a community of "hackers" located in and around Greenville, SC. Our
                            community exists to foster personal growth for community
                            members through sharing and promoting local tech opportunities.
                        </p>
                    </div>

                    <hr class="d-md-none">

                    <div class="row mt-5 align-items-center">
                        <div class="col-md-6 text-center">
                            <img src="{{url('img/meetup.jpeg')}}" alt="Join Us" class="img-fluid">
                        </div>
                        <div class="col-md-6">
                            <p class="summary">
                                HG is the <code>"GO TO"</code> resource for discovering and connecting with Upstate SC
                                tech hackers, makers, and tinkerers.
                            </p>
                            <p class="summary">
                                Explore the site for more meetups and events, and make sure to join our active <a
                                    href="/join-slack">Slack community</a> to connect further!
                            </p>
                            <button onclick="location.href='/join-slack'" class="btn btn-outline-primary btn-lg">
                                Join Us
                            </button>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-6 my-5">
                            <h2 class="display-5 text-center mt-5">Contribute</h2>
                            <div class="summary container">
                                hackgreenville.com is built on the
                                <a href="https://laravel.com/">Laravel</a> PHP framework
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <a href="https://github.com/hackgvl/hackgreenville-com"
                               class="text-decoration-none text-dark">
                                <p style="line-height:1; font-size:15em;" class="mt-5">
                                    <i class="fa fa-github"></i>
                                </p>
                                <p class="summary">Join the Project</p>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mt-sm-5 mt-md-3">
                    <div>
                        <h3 class="text-center">
                            Upcoming Events
                        </h3>
                        <ul
                            @class(['timeline'=>$upcoming_events->isNotEmpty()])
                        >
                            @if ($upcoming_events->isEmpty())
                                <li>
                                    <strong>No</strong> events to display.
                                </li>
                            @else
                                @foreach ($upcoming_events as $event)
                                    <li class="timeline-inverted">
                                        <div class="timeline-badge bg-success">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <h4 class="timeline-title">
                                                    @if($event->cancelled_at)
                                                        <div class="text-danger">
                                                            [CANCELLED]
                                                        </div>
                                                    @endif
                                                    {{ $event->event_name }}
                                                </h4>
                                                <p class="timeline-subtitle h6">
                                                    {{ $event->organization->title }}
                                                </p>
                                                <p>
                                                    <small class="text-muted">
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
