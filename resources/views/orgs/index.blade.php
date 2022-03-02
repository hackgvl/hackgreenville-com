@extends('layouts.app')

@section('title', 'Tech Organizations near Greenville, SC')
@section('description', 'A list of tech meetups, code schools, tech conferences / hack-a-thons near Greenville, SC, including inactive organizations.')

@section('content')
    <div class="container">

        <h1>Local Tech Organizations</h1>

        <div class="row">
            @foreach ($activeOrgs as $cat_inc => $category)
                <div class="col-md-6  mb-3">
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
                                        <a href="{{ $org->url}}" target="_blank" title="Homepage">{{ $org->title }}</a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{$org->event_calendar_uri }}" target="_blank">Events Site</a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                </div>
            @endforeach
        </div>

        <ul>
            <li>You can view <a href="{{ route('orgs.inactive') }}">inactive organizations here</a>.
            </li>
            <li>This data is sourced from <a href="https://data.openupstate.org" target="_blank">a community-curated
                    REST API</a>.
            </li>
            <li>To contribute to this project, please connect with <a href="https://codeforgreenville.org"
                                                                      target="_blank">Code For Greenville.</a></li>
            <li>To suggest an addition or update to the data, please submit a <a
                        href="https://data.openupstate.org/contact/suggestions">suggestion</a>.
            </li>
        </ul>

    </div>
@endsection
