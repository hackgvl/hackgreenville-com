@extends('layouts.app')

@section('title', 'Hackgreenville - A Developer Community in the Greenville SC Area')
@section('description', "HackGreenville exists to foster personal growth among the 'hackers' of Greenville, SC and the surrounding area.")

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
