@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="text-center">
            <div class="codey-container mb-3 mt-5">
                <img src="{{asset('img/Codey-500.png')}}" alt="codey" class="codey-face"/>
            </div>
            <h2><strong>Codey</strong> is sorry, there is some issue going on.</h2>
            <h3>
                <a href="{{url('/')}}" title="Home">Go Home</a>
            </h3>
        </div>

    </div>
@endsection
