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
                        <a href="https://hackgreenville.slack.com" class="badge badge-pill btn-success p-3" rel="noreferrer" target="_blank">
                            Already Signed Up? Log In to Slack
                        </a>
                    </h4>

                    <p class="summary">
                        {{ __('Ready to get started? Fill out the sign up form below and we\'ll add you as soon as possible!') }}
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
                    {{ aire()->textArea('reason', __('What do you do in the Upstate?'))->rows(4)->placeholder(__('What is it that interests you? What kinds of things are you involved in here? If you are not from the Upstate and don\'t plan to be, you might find another group better suited for you.')) }}

                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <p>The Rules of HackGreenville are simple:</p>
                    <ul class="text-left">
                        <li>
                            <strong>Be nice:</strong>
                            We're a community that helps one another regardless of Gender,
                            Religion, Political Party, Programming Language, Tabs or Spaces... If you
                            try to provoke fights you will be removed.
                        </li>
                        <li>
                            <strong>Be considerate:</strong>
                            Don't @channel or @here in heavily used rooms like #random
                            and #opportunities
                        </li>
                        <li>
                            <strong>Make it happen:</strong>
                            See a need for a new channel around a topic? Feel out the
                            interest level and then just make it! (If it becomes a ghost town we might
                            just archive it)
                        </li>
                        <li>
                            <strong>Don't SPAM!:</strong>
                            Seriously, don't do it.
                            We're always glad to hear what community members are working on,
                            but if you're here just to spam members about a new service or outsourcing,
                            just don't sign up, you will be banned. This is a local community, not an
                            advertising market (unless it made by locals who contribute to the group)
                        </li>
                        <li>
                            <strong>Recruiters:</strong>
                            If you have jobs in the Upstate, SC area, you are welcome, but
                            we ask that you please keep job postings to the #recruiting channel only.
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
