@extends('layouts.app', ['remove_space' => true])

@section('title', 'Hackgreenville - A Developer Community in the Greenville SC Area')

@section('content')

    <div id="carouselIndicators" class="homepage carousel slide bg-primary" data-ride="carousel" data-interval="10000">
        {{--<ol class="carousel-indicators">
            <li data-target="#carouselIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselIndicators" data-slide-to="1"></li>
        </ol>--}}
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block align-center" src="{{asset('/img/hackgreenville-banner.jpg')}}" alt="Greenville works together"/>
                <div class="carousel-caption text-center text-lg-right">
                    <h1 class="p-3">Come watch our talks</h1>
                    <span class="text bg-blue p-3">
                            Fun and informative modern talks
                        </span>
                </div>
            </div>
        </div>
        {{--<a class="carousel-control-prev" href="#carouselIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>--}}
    </div>

    <div class="container-fluid">
        <div class="row my-5">
            <div class="col-md-6 col-lg-7 text-center">
                <h3 title="[content placeholder]">[<i>CONTENT</i> <strong>PLACEHOLDER</strong>]</h3>
            </div>
            <div class="col-md-6 col-lg-5">
                <hg-timeline event_data_route="{{route('api.homepage.timeline', [], false)}}" title="Upcoming Events">
                    <ul>
                        <li class="list-unstyled">
                            Loading events <i class="fa fa-spinner fa-spin fa-2x"></i>
                        </li>
                    </ul>
                </hg-timeline>
            </div>
        </div>
    </div>
@endsection

