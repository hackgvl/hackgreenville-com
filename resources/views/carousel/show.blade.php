@extends('layouts.app')

@section('breadcrumbs')
    <li class="breadcrumb-item">{{__('Authed')}}</li>
    <li class="breadcrumb-item">
        <a href="{{route('authed.carousel.index')}}">
            {{__('Carousels')}}
        </a>
    </li>
    <li class="breadcrumb-item active">{{$carousel->name}}</li>
@endsection

@section('content')
    <div class="container">
        <div class="row ">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="pull-right">
                            <a href="{{route('authed.carousel.index')}}" title="Cancel" class="btn btn-primary">
                                <i class="fa fa-arrow-left"></i>
                                <span class="d-none d-md-inline">
                                    Back
                                </span>
                            </a>
                        </div>
                        Carousel Show
                    </div>

                    <div class="card-body">

                        <carousel-images carousel_id="{{$carousel->id}}"></carousel-images>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
