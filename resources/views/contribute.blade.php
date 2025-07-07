@extends('layouts.app')

@section('title', 'Contribute Around Greenville, SC')
@section('description', "Opportunties to sponsor, volunteer, and donate with Upstate, SC tech, maker, and tinker non-profits or open-source projects.")

@section('content')
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h1>Contribute</h1>
                    <p class="summary">Support the local organizations making things happen for our local tech workforce, makers, and tinkerers.</p>
                    <ul>
                        <li>Volunteer your time or resources to a project or program</li>
                        <li>Get the spotlight by sponsoring an event or initiative</li>
                        <li>Donate money to our local non-profits</li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-4">
                    <div class="card">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-primary"><h2>Volunteer</h2></li>
                            <li class="list-group-item"><a href="/orgs/agile-learning-institute">Agile Learning Institute</a></li>
                            <li class="list-group-item"><a href="/orgs/carolina-code-conference">Carolina Code Conference</a></li>
                            <li class="list-group-item"><a href="/orgs/code-with-the-carolinas">Code with the Carolinas</a></li>
                            <li class="list-group-item"><a href="/labs">HackGreenville Labs</a></li>
                            <li class="list-group-item"><a href="/hg-nights">HackGreenville Nights</a></li>
                            <li class="list-group-item"><a href="/orgs/synergy-mill">Synergy Mill</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-sm-12 col-md-4">
                    <div class="card">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-primary"><h2>Sponsor</h2></li>
                            <li class="list-group-item"><a href="/orgs">Local Meetup Groups</a></li>
                            <li class="list-group-item"><a href="/orgs/build-carolina">Build Carolina</a></li>
                            <li class="list-group-item"><a href="/hg-nights">HackGreenville Nights</a></li>
                            <li class="list-group-item"><a href="https://refactorgvl.com">RefactorGVL</a></li>
                            <li class="list-group-item"><a href="/orgs/synergy-mill">Synergy Mill</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4">
                    <div class="card">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-primary"><h2>Donate</h2></li>
                            <li class="list-group-item"><a href="/orgs/agile-learning-institute">Agile Learning Institute</a></li>
                            <li class="list-group-item"><a href="/orgs/build-carolina">Build Carolina</a></li>
                            <li class="list-group-item"><a href="https://refactorgvl.com">RefactorGVL</a></li>
                            <li class="list-group-item"><a href="/orgs/synergy-mill">Synergy Mill</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
@endsection
