@extends('layouts.app', ['remove_space' => true])

@section('title', 'Hackgreenville - A Developer Community in the Greenville SC Area')

@section('content')
    <div id="contact">
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
