@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center">
            <div class="codey-container mb-3 mt-12 flex justify-center">
                <img src="{{asset('img/Codey-500.png')}}" alt="codey" class="codey-face"/>
            </div>
            <h2 class="text-3xl mb-4"><strong>Codey</strong> is sorry, there is some issue going on.</h2>
            <h3 class="text-xl">
                <a href="{{url('/')}}" title="Home" class="text-primary underline hover:text-blue-600">Go Home</a>
            </h3>
        </div>
    </div>
@endsection
