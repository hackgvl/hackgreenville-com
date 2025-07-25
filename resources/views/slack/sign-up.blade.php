@extends('layouts.app', ['remove_space' => true])

@section('title', 'HackGreenville Slack Sign-up and Login Info')
@section('description', 'The sign-up form to request access to the HackGreenville Slack.')

@section('content')
    <div id="join-slack">
        <div class="container">
            <div class="row">
                <div class="col-12 mx-1 mt-5 text-center">
                    <h1>{{ __('Sign up for HackGreenville!') }}</h1>

                    <h4>
                        <a href="https://hackgreenville.slack.com" class="badge badge-pill btn-success p-3" rel="nofollow" target="_blank">
                            Already Signed Up? Log In to Slack
                        </a>
                    </h4>

                    <p class="summary">
                        {{ __('Ready to get started? Fill out the form below and we\'ll add you as soon as possible!') }}
                    </p>
                </div>
            </div>

            <div class="row">
                <hr class="mx-auto w-100 px-4" style="max-width: 50em;">
            </div>

            {{ aire()->route('join-slack.submit') }}
            <div class="row justify-content-center">
                <div class="col-md-5">
                    {{ aire()->summary()->verbose() }}

                    {{ aire()->input('name', __('Full Name'))->required() }}
                    {{ aire()->email('contact', __('Email'))->required() }}
                    {{ aire()->textArea('reason', __('Share why you are joining HackGreenville and include any relevant local context that helps validate you\'re not a bot or spammer.'))->rows(4)->placeholder(__('What interests you about HackGreenville? What connections do you have to the Upstate of South Carolina?')) }}
                    {{ aire()->url('url', __('Please provide a LinkedIn profile, or similar link, that validates details entered on this form. This helps us filter out otherwise convincing bots and spammers.'))->placeholder('https://linkedin.com/in/not-a-bot') }}

                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <p>The Rules of HackGreenville are simple:</p>
                    <ul class="text-left">
                        <li><strong>Everyone agrees to abide by the <a href="{{route('code-of-conduct')}}" target="_blank">Code of Conduct</a></strong>
                          within our Slack and at in-person events.
                            We <a href="{{route('about')}}" target="_blank">exist to nurture personal growth</a>, not to bring people down.
                            Constructive debate, even "Tabs" or "Spaces", is welcome, but please do not harrass or provoke.
                        </li>
                        <li>
                            <strong>Be considerate:</strong>
                            Don't @channel or @here
                        </li>
                        <li>
                            <strong>Don't self-promote:</strong>
                            We're glad to hear what regular community members are working on,
                            but do not spam members with offers.
                            If you want the spotlight, then consider <a href="/contribute">sponsoring</a>.
                        </li>
                        <li>
                            <strong>Recruiters:</strong>
                            Job postings where the company isn't disclosed are limited to the #recruiting channel.
                        </li>
                        <li>
                            <strong>Make it happen:</strong>
                            See a need for a new channel around a topic? Feel out the
                            interest level and then just make it! (If it becomes a ghost town we might
                            just archive it)
                        </li>
                    </ul>

                    <div class="text-center">
                        {{ aire()->checkbox('rules', __('Do you accept the rules?'))->required() }}
                        {!! HCaptcha::display(['class' => 'hcaptcha mt-4']) !!}
                        <script src="https://js.hcaptcha.com/1/api.js" async defer></script>

                        {{ aire()->submit('Join Slack')->addClass('m-4') }}
                    </div>
                </div>
            </div>
            {{ aire()->close() }}
        </div>
    </div>
@endsection
