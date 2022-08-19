@extends('layouts.app', ['remove_space' => true])

@section('title', 'HackGreenville Slack Sign-up and Login Info')
@section('description', 'The sign-up form to request access to the HackGreenville Slack.')

@section('content')
    <div id="join-slack">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 mx-1 mt-5 text-center">
                    <h1 class="mb-3">{{ __('Thank you!') }}</h1>

                    <p class="summary">
                        {{ __('We\'ll try to get back to you as soon as possible!') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
