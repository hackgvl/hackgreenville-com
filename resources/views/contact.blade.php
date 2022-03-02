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

            <form method="POST" action="/contact/submit"
                class="row mx-auto px-5 mb-4 text-center justify-content-center align-items-center"
                style="max-width: 50em;">
                @csrf

                <!-- name -->
                <label for="name" class="col-12 my-1">{{ __('Name') }}</label>

                <input id="name"
                    type="text"
                    name="name"
                    class="text-center col-12 mx-1 my-2 form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}">

                @error('name')
                    <div class="col-12 mx-1 my-2 alert alert-danger justify-content-center align-items-center">{{ $message }}</div>
                @enderror

                <!-- contact -->
                <label for="contact" class="col-12 my-1">{{ __('Email') }}</label>

                <input id="contact"
                    type="email"
                    name="contact"
                    class="text-center col-12 mx-1 my-2 form-control @error('contact') is-invalid @enderror"
                    value="{{ old('contact') }}">

                @error('contact')
                    <div class="col-12 mx-1 my-2 alert alert-danger justify-content-center align-items-center">{{ $message }}</div>
                @enderror

                <!-- message -->
                <label for="message" class="col-12 my-1">{{ __('Message') }}</label>

                <textarea id="message" name="message"
                    class="col-12 mx-1 my-2 form-control @error('message') is-invalid @enderror">{{ old('message') }}</textarea>

                @error('message')
                    <div class="col-12 mx-1 my-2 alert alert-danger justify-content-center align-items-center">{{ $message }}</div>
                @enderror

                <button type="submit" class="my-4 btn btn-outline-secondary">
                    {{ __('Submit') }}
                </button>
            </form>
        </div>
    </div>
@endsection
