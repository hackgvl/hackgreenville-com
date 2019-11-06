@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="text-center">
            <div class="codey-container mb-3 mt-5">
                <img src="{{asset('img/codey-face.png')}}" alt="codey" class="codey-face"/>
                <div class="expression">404</div>
            </div>
            <h2>OOPS! <strong>Codey</strong> says the page is missing?</h2>
            <h3>
                <a href="{{url('/')}}" title="Home"><i class="fa fa-home"></i> Home</a>
            </h3>
        </div>

    </div>
@endsection
