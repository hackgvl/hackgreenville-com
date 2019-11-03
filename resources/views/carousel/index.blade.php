@extends('layouts.app')

@section('breadcrumbs')
    <li class="breadcrumb-item">{{__('Authed')}}</li>
    <li class="breadcrumb-item">{{__('Carousels')}}</li>
@endsection

@section('content')
    <div class="container">
        <div class="row ">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="pull-right">
                            <a href="{{route('authed.carousel.create')}}" title="Create" class="btn btn-primary">
                                <i class="fa fa-plus-circle"></i>
                                <span class="d-none d-md-inline">
                                    Create
                                </span>
                            </a>
                        </div>
                        <h2>
                            Carousel List
                        </h2>
                    </div>

                    <div class="card-body">

                        {!! $carousels->links() !!}

                        <table class="table table-hover table-stripped table-hover">
                            <tr>
                                <th>ID</th>
                                <th>Slug</th>
                                <th>Name</th>
                                <th class="options">Options</th>
                            </tr>
                            @forelse($carousels as $carousel)
                                <tr>
                                    <td class="number">{{$carousel->id}}</td>
                                    <td>
                                        <a href="{{route('authed.carousel.show', $carousel)}}" title="Edit">
                                            {{$carousel->slug}}
                                        </a>
                                    </td>
                                    <td>{{$carousel->name}}</td>
                                    <td class="options">
                                        <a href="{{route('authed.carousel.show', $carousel)}}" title="Slide management" class="btn btn-primary">
                                            <i class="fa fa-eye"></i>
                                            <span class="d-none d-md-inline">
                                            Show
                                        </span>
                                        </a>
                                        <a href="{{route('authed.carousel.edit', $carousel)}}" title="Edit" class="btn btn-secondary">
                                            <i class="fa fa-pencil"></i>
                                            <span class="d-none d-md-inline">
                                            Edit
                                        </span>
                                        </a>
                                        <a href="#" onclick="event.preventDefault(); document.getElementById('delete-carousel-{{$carousel->id}}').submit();" title="Delete" class="btn btn-danger">
                                            <i class="fa fa-trash"></i>
                                            <span class="d-none d-md-inline">
                                            Delete
                                        </span>
                                        </a>
                                        {!! Form::open(['id' => "delete-carousel-{$carousel->id}", 'route' => ['authed.carousel.destroy', $carousel], 'method' => 'delete', 'class' => 'd-none']) !!}
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100">No Active Carousels</td>
                                </tr>
                            @endforelse
                        </table>

                        {!! $carousels->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
