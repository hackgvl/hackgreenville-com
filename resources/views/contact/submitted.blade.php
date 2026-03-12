@extends('layouts.app', ['remove_space' => true])

@section('title', 'Contact HackGreenville')
@section('description', 'Send non-spammy questions or comments to the HackGreenville community.')

@section('content')
    <div id="contact">
        <div class="max-w-4xl mx-auto px-4">
            <div class="text-center mt-20">
                <h1 class="text-3xl font-bold mb-3">{{ __('Thank you!') }}</h1>

                <p class="text-base">
                    {{ __('We\'ll try to get back to you as soon as possible!') }}
                </p>
            </div>
        </div>
    </div>
@endsection
