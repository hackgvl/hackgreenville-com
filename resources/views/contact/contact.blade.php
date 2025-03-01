@extends('layouts.app')

@section('title', 'Contact HackGreenville')
@section('description', 'Send non-spammy questions or comments to the HackGreenville community.')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="row mx-2 mx-lg-0">
            <div class="col-12">

                <div class="row">
                    <div class="col-12">
                        <h1>Contact HackGreenville</h1>
                        <ul class="values-list">
                            <li>See our <a href="/code-of-conduct" class="highlight-link">Code of Conduct</a> for related concerns.</li>
                            <li>All other requests may be shared through the form below.</li>
                        </ul>
                    </div>
                </div>

                <div class="row">
                    <hr class="mx-auto w-100 px-4">
                    <h2>Contact Form</h2>
                </div>

                {{ aire()->route('contact.submit') }}

                {{ aire()->input('name', 'Name') }}
                {{ aire()->email('contact', 'Email') }}
                {{ aire()->textArea('message', 'Message') }}

                {!! HCaptcha::display(['class' => 'hcaptcha mt-4 text-center']) !!}

                @error('h-captcha-response')
                <div class="col-12 mx-1 my-2 alert alert-danger">{{ $message }}</div>
                @enderror

                <div class="text-center">
                    {{ aire()->submit() }}
                </div>
                {{ aire()->close() }}
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
    @endpush
@endsection
