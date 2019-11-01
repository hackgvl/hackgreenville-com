@extends('layouts.app')

@section('breadcrumbs')
    <li class="breadcrumb-item">{{__('Authed')}}</li>
    <li class="breadcrumb-item">
        <a href="{{route('authed.carousel.index')}}">
            {{__('Carousel')}}
        </a>
    </li>
    <li class="breadcrumb-item active">{{__('Create')}}</li>
@endsection

@section('content')
    <div class="container">
        <div class="row ">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="pull-right">
                            <a href="{{route('authed.carousel.index')}}" title="Cancel" class="btn btn-primary">
                                <i class="fa fa-plus-circle"></i>
                                <span class="d-none d-md-inline">
                                    Cancel
                                </span>
                            </a>
                        </div>
                        Carousel Create
                    </div>

                    <div class="card-body">

                        {!! Form::open(['route' => 'authed.carousel.store', 'method' => 'post']) !!}

                        @include('carousel.fields')

                        @include('partials.form-buttons')
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
