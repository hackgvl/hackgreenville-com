@extends('layouts.app', ['remove_space' => true])

@section('title', 'HackGreenville Nights')
@section('description', 'An evening where Greenville\'s tech community gathers for fellowship and short-format talks')

@section('content')
  <div class="container">
    <div
      id="hg-nights-jumbotron"
      class="row text-white jumbotron jumbotron-fluid d-flex flex-column align-items-stretch"
    >
      <div class="col-sm-10 offset-md-2 col-md-4 py-5  justify-content-center text-center">
        <h1 class="display-1">{{ __('HackGreenville Nights') }}</h1>
        <div class="p-2 text-primary bg-light rounded col-sm-12">
          <p class="lead">
            A “Quarterly-ish” Gathering of Greenville's Tech Community Coming
            Together to Learn From Each Other and to Have Fellowship With One Another.
          </p>
          <a
            href="https://www.meetup.com/hack-greenville/"
            type="button"
            class="btn btn-success font-weight-bold"
            role="button"
            target="_blank">
              Join our Meetup group to be notified of future events!
          </a>
        </div>
      </div>
    </div>
    <div class="col-10 px-5 offset-2 col-sm-8 offset-sm-4 col-xl-12 offset-xl-2 mt-5 mx-auto d-flex flex-column">
      <h2 class="font-weight-bold">{{ __('How to Get Involved') }}</h2>
      <ul class="list-group list-group-flush">
        <li class="list-group-item">Join our <a href="https://www.meetup.com/hack-greenville/">Meetup group</a> to receive updates on future events and come hang out!</li>
        <li class="list-group-item">Hop into the <a href="/join-slack">official HackGreenville Slack channel</a> to join the conversation and connect with local developers.</li>
        <li class="list-group-item">Share a passion, neat project, or anything of interest by <a href="/contact">submitting a lightning talk</a> to present at an event.</li>
        <li class="list-group-item"><a href="/contact">Volunteer</a> to assist with facilitating a gathering</li>
        <li class="list-group-item">Help make HG Nights possible by <a href="/contact">becoming a sponsor</a></li>
      </ul>
    </div>
    <div
      class="col-10 px-5 offset-2 col-sm-8 offset-sm-4 col-xl-12 offset-xl-2 mt-5 mx-auto d-flex flex-column"
    >
      <h2 class="font-weight-bold">{{ __('Past Talks') }}</h2>
    </div>
  </div>
@endsection
