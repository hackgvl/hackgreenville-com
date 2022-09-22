@extends('layouts.app')

@section('title', 'Contact HackGreenville')
@section('description', 'Send non-spammy questions or comments to the HackGreenville community.')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="row mx-2 mx-lg-0">
            <div class="col-12">

                <div class="row">
                    <div class="col-12 text-center">
                        <h1>{{ __('Contact Us') }}</h1>

                        <p class="summary">
                            {{ __('Have a question? Fill out the contact form below and we\'ll try to get back to you as soon as possible!') }}
                        </p>
                    </div>
                </div>

                <div class="row">
                    <hr class="mx-auto w-100 px-4">
                </div>

                {!! Form::open(['url' => url()->secure('/contact'), 'class'=> 'px-lg-4']) !!}
                <div class="form-group">
                    {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                    {{ Form::text('name', old('name'), ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : null)]) }}
                    @error('name')
                    <div class="col-12 mx-1 my-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    {{ Form::label('contact', __('Email'), ['class' => 'form-label']) }}
                    {{ Form::email('contact', old('contact'), ['class' => 'form-control' . ($errors->has('contact') ? ' is-invalid' : null)]) }}
                    @error('contact')
                    <div class="col-12 mx-1 my-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    {{ Form::label('message', __('Message'), ['class' => 'form-label']) }}
                    {{ Form::textarea('message', old('message'), ['class' => 'form-control' . ($errors->has('message') ? ' is-invalid' : null)]) }}
                    @error('message')
                    <div class="col-12 mx-1 my-2 alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                {!! HCaptcha::display(['class' => 'hcaptcha mt-4 text-center']) !!}

                @error('h-captcha-response')
                <div class="col-12 mx-1 my-2 alert alert-danger">{{ $message }}</div>
                @enderror

                <div class="text-center">
                    {{ Form::submit('Submit', ['class' => 'my-4 btn btn-outline-secondary ']) }}
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
    @endpush
@endsection
