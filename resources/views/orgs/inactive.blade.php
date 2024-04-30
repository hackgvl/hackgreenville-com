@extends('layouts.app')

@section('title', 'Organizations')

@section('content')
    <div class="container">

        <h1>Local Tech Organizations <span class="text-danger">(Inactive Groups)</span></h1>

        <div class="row">
            @forelse ($inactiveOrgs as $cat_inc => $category)
                <div class="col-md-{{$inactiveOrgs->count() == 1? 12: 6}} mb-3">
                    <ul class="list-group">
                        <li class="list-group-item list-group-item-primary">
                            <h2>
                                <div class="pull-right">
                                    {{$category->count()}}
                                </div>
                                {{$category[0]->category->label}}
                            </h2>
                        </li>

                        @foreach($category as $org)
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-6">
                                        <a href="{{ route('orgs.show', $org) }}" title="{{ $org->title }}">{{ $org->title }}</a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{$org->event_calendar_uri }}" rel="external">Events Site</a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @empty
                <div class="col-md-12">
                    <div class="text-center">
                        <h3 class="text-white">
                            YAY
                        </h3>
                        <h2>No inactive groups at this time</h2>
                        <h3 class="text-white">
                            Sorry to disappoint
                        </h3>
                    </div>
                </div>
            @endforelse
        </div>

        <ul>
            <li>This data is sourced from <a href="https://data.openupstate.org" rel="external">a community-curated
                    REST API</a>.
            </li>
            <li>To contribute to this project, please connect with <a href="https://codeforgreenville.org"
                                                                      rel="external">HackGreenville Labs.</a></li>
            <li>To suggest an addition or update to the data, please submit a <a
                        href="https://data.openupstate.org/contact/suggestions">suggestion</a>.
            </li>
        </ul>

    </div>
@endsection
