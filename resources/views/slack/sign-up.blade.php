@extends('layouts.app', ['remove_space' => true])

@section('title', 'HackGreenville Slack Sign-up and Login Info')
@section('description', 'The sign-up form to request access to the HackGreenville Slack.')

@section('content')
    <div id="join-slack" class="bg-gray-100 min-h-screen py-12">
        <div class="container max-w-4xl mx-auto px-4">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold mb-6 text-gray-800">{{ __('Sign up for HackGreenville!') }}</h1>

                <div class="mb-6">
                    <a href="https://hackgreenville.slack.com" class="inline-block bg-success text-white px-6 py-3 rounded-full font-semibold hover:bg-green-600 transition-colors" rel="nofollow" target="_blank">
                        Already Signed Up? Log In to Slack
                    </a>
                </div>

                <p class="text-lg text-gray-700 mb-8">
                    {{ __('Ready to get started? Fill out the form below and we\'ll add you as soon as possible!') }}
                </p>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-8 max-w-2xl mx-auto">
                {{ aire()->route('join-slack.submit') }}
                
                {{ aire()->summary()->verbose() }}

                <div class="mb-6">
                    {{ aire()->input('name', __('Full Name'))->required()->addClass('w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary') }}
                </div>

                <div class="mb-6">
                    {{ aire()->email('contact', __('Email'))->required()->addClass('w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary') }}
                </div>

                <div class="mb-6">
                    {{ aire()->textArea('reason', __('Share why you are joining HackGreenville and include any relevant local context that helps validate you\'re not a bot or spammer.'))->rows(4)->placeholder(__('What interests you about HackGreenville? What connections do you have to the Upstate of South Carolina?'))->addClass('w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary h-32 resize-vertical') }}
                </div>

                <div class="mb-8">
                    {{ aire()->url('url', __('Please provide a LinkedIn profile, or similar link, that validates details entered on this form. This helps us filter out otherwise convincing bots and spammers.'))->placeholder('https://linkedin.com/in/not-a-bot')->addClass('w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary') }}
                </div>
                <div class="mb-8">
                    <p class="text-lg font-semibold mb-4">The Rules of HackGreenville are simple:</p>
                    <ul class="text-left space-y-4 text-gray-700">
                        <li><strong>Everyone agrees to abide by the <a href="{{route('code-of-conduct')}}" target="_blank" class="text-primary hover:underline">Code of Conduct</a></strong>
                          within our Slack and at in-person events.
                            We <a href="{{route('about')}}" target="_blank" class="text-primary hover:underline">exist to nurture personal growth</a>, not to bring people down.
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
                            If you want the spotlight, then consider <a href="/contribute" class="text-primary hover:underline">sponsoring</a>.
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
                </div>

                <div class="text-center">
                    <div class="mb-6">
                        {{ aire()->checkbox('rules', __('Do you accept the rules?'))->required()->addClass('mr-2') }}
                    </div>
                    
                    <div class="mb-6">
                        {!! HCaptcha::display(['class' => 'hcaptcha']) !!}
                    </div>

                    {{ aire()->submit('Join Slack')->addClass('bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors cursor-pointer') }}
                </div>
                {{ aire()->close() }}
            </div>
        </div>
    </div>
    
    @push('scripts')
        <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
    @endpush
@endsection
