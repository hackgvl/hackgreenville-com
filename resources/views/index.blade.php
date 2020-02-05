@extends('layouts.app', ['remove_space' => true])

@section('title', 'Hackgreenville - A Developer Community in the Greenville SC Area')

@section('content')
    <div id="homepage">
        <div id="homepage-jumbotron" class="jumbotron jumbotron-fluid">
            <div class="overlay"></div>
            <div class="container font-weight-bold">
                <h1 class="display-2">Build Stuff. Meet People.</h1>
                <p class="lead">Meetups &middot; Talks &middot; Projects</p>
                <hr class="my-2">
                <p class="lead">
                    <a class="btn btn-primary btn-lg" href="/join-slack" role="button">Request to Join Slack</a>
                </p>
            </div>
        </div>

        <div class="container-fluid pl-5">
            <div class="row">
                <div class="col-md-6 col-lg-8">
                <h3 class="display-4">What is Hack Greenville?</h3>

                    <p class="summary">
                        Hack Greenville is a community of "hackers" located in and around Greenville, SC. Our community exists to foster personal growth for community
                        members through sharing and promoting local tech opportunities.
                    </p>
                    <hr class="d-md-none">

                    <div class="row">
                        <div class="col-md-6">
                            <img src="{{url('img/meetup.jpeg')}}" alt="Join Us" style="max-width: 100%; height: 20rem"/>
                        </div>
                        <div class="col-md-6">
                            <p class="summary">
                                HG is the <code>"GO TO"</code> resource for discovering and connecting with Upstate SC</strong> tech hackers, makers, and tinkerers.
                            </p>
                            <p class="summary">
                                Explore the site for more meetups and events, and make sure to join our active <a href="/join-slack">Slack community</a> to connect further!
                            </p>
                            <button onclick="location.href='/join-slack'" class="btn btn-outline-primary">
                                Join Us
                            </button>
                        </div>
                    </div>

                    <h2 class="page-title mt-5">Contribute</h2>

                    <div class="row">
                        <div class="col-md-6 my-5 text-center">
                            <p style="line-height:1; font-size:15em;">
                                <a href="https://github.com/codeforgreenville/hackgreenville-com">
                                <i class="fa fa-github"></i>
                                </a>
                            <p/>
                            <p class="summary">Join the Project</p>
                        </div>
                        <div class="col-md-6">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mt-sm-5 mt-md-3">
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

