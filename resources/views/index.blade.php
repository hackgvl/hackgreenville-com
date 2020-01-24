@extends('layouts.app', ['remove_space' => true])

@section('title', 'Hackgreenville - A Developer Community in the Greenville SC Area')

@section('content')
    <div id="homepage">
        <div id="homepage-jumbotron" class="jumbotron jumbotron-fluid">
            <div class="overlay"></div>
            <div class="container font-weight-bold">
                <h1 class="display-3">Be a part of something fun</h1>
                <p class="lead">Meetups &middot; Talks &middot; Projects</p>
                <hr class="my-2">
                <p>More info</p>
                <p class="lead">
                    <a class="btn btn-primary btn-lg" href="/join-slack" role="button">Request to Join Slack</a>
                </p>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row my-5">
                <div class="col-md-6 col-lg-8 text-center">
                    <p title="Page title" class="page-title">What is Hack Greenville?</p>
                    <p class="summary p-md-5">
                        Hack Greenville is a community of "hackers" located in and around Greenville, SC. Our community exists to foster personal growth for community members through sharing and promoting local tech
                        opportunities. Greenville is a great place to live and build community and <strong>HG is THE go-to resource for discovering and connecting with what is happening in the Upstate</strong> hacker,
                        maker and tinkerer space! To the right is a feed of upcoming events, feel free to explore the site for more meetups and events, and make sure to join our active
                        <a href="/join-slack">Slack community</a> to connect further!
                    </p>

                    <hr class="d-md-none">

                    <div class="row">
                        <div class="col-md-6">
                            <img src="{{url('img/meetup.jpeg')}}" alt="Join Us" style="max-width: 100%; height: 20rem"/>
                        </div>
                        <div class="col-md-6">
                            <button onclick="location.href='/join-slack'" class="btn btn-outline-primary my-5">
                                JOIN US
                            </button>
                        </div>
                    </div>

                    <hr class="d-md-none">

                    <p class="page-title mt-5">Sponsors</p>

                    <div class="row">
                        <div class="col-md-6 my-5">
                            Our amazing sponsors help to make this possible.
                        </div>
                        <div class="col-md-6">
                            <img src="{{url('img/icons/sponsors.png')}}" alt="Sponsors" style="max-width: 100%; height: 20rem"/>
                        </div>
                    </div>

                </div>
                <div class="col-md-6 col-lg-4 mt-sm-5 mt-md-3">
                    <hr class="d-md-none my-5">
                    <hg-timeline event_data_route="{{route('api.homepage.timeline', [], false)}}" title="Upcoming Events">
                        <ul>
                            <li class="list-unstyled">
                                Loading events <i class="fa fa-spinner fa-spin fa-2x"></i>
                            </li>
                        </ul>
                    </hg-timeline>
                </div>
            </div>
        </div>
    </div>
@endsection

