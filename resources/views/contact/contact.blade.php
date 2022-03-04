@extends('layouts.app', ['remove_space' => true])

@section('title', 'Hackgreenville - A Developer Community in the Greenville SC Area')

@section('content')
<div id="contact">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mx-1 mt-5 text-center">
                <h1>{{ __('Contact Us') }}</h1>

                <p class="summary">
                    {{ __('Have a question? Fill out the contact form below and we\'ll try to get back to you as soon as possible!') }}
                </p>
            </div>
        </div>

        <div class="row">
            <hr class="mx-auto w-100 px-4" style="max-width: 50em;">
        </div>

        {!! Form::open(['url' => url()->secure('/contact'), 'class' => 'row mx-auto px-5 mb-4 text-center justify-content-center align-items-center', 'style' => 'max-width: 50em;']) !!}
            <div class="form-group col-12 row">
                {{ Form::label('name', __('Name'), ['class' => 'col-12 form-label']) }}
                {{ Form::text('name', old('name'), ['class' => 'col-12 form-control text-center mx-1 my-2' . ($errors->has('name') ? ' is-invalid' : null)]) }}
                @error('name')
                    <div class="col-12 mx-1 my-2 alert alert-danger justify-content-center align-items-center">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group col-12 row">
                {{ Form::label('contact', __('Email'), ['class' => 'col-12 form-label']) }}
                {{ Form::email('contact', old('contact'), ['class' => 'col-12 form-control text-center mx-1 my-2' . ($errors->has('contact') ? ' is-invalid' : null)]) }}
                @error('contact')
                    <div class="col-12 mx-1 my-2 alert alert-danger justify-content-center align-items-center">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group col-12 row">
                {{ Form::label('message', __('Message'), ['class' => 'col-12 form-label']) }}
                {{ Form::textarea('message', old('message'), ['class' => 'col-12 form-control text-center mx-1 my-2' . ($errors->has('message') ? ' is-invalid' : null)]) }}
                @error('message')
                    <div class="col-12 mx-1 my-2 alert alert-danger justify-content-center align-items-center">{{ $message }}</div>
                @enderror
            </div>

            {!! HCaptcha::display(['class' => 'hcaptcha col-12 mt-4']) !!}
            <script src="https://js.hcaptcha.com/1/api.js" async defer></script>

            @error('h-captcha-response')
                <div class="col-12 mx-1 my-2 alert alert-danger justify-content-center align-items-center">{{ $message }}</div>
            @enderror

            {{ Form::submit('Submit', ['class' => 'my-4 btn btn-outline-secondary']) }}
        {!! Form::close() !!}
    </div>
</div>
@endsection
