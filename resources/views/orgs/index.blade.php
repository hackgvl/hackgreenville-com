@extends('layouts.app')

@section('title', 'Tech Organizations near Greenville, SC')
@section('description', 'A list of tech meetups, code schools, tech conferences / hack-a-thons near Greenville, SC, including inactive organizations.')

@section('content')
    <div class="container">

        <h1>Local Tech Organizations</h1>
        <div class="card-columns">
            @foreach ($activeOrgs as $organizations)
                <div class="card mb-3 d-inline-block">
                    <ul class="list-group">
                        <li class="list-group-item list-group-item-primary">
                            <h2>
                                <div class="pull-right">
                                    {{$organizations->count()}}
                                </div>
                                {{$organizations->first()->category->label}}
                            </h2>
                        </li>

                        @foreach($organizations as $org)
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-6">
                                        <a href="{{ route('orgs.show', $org) }}" title="Homepage"
                                            @class([
                                                'text-muted text-decoration-line-through' => $org->category->isInactive()
                                            ])
                                        >
                                            {{ $org->title }}
                                        </a>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        @if($org->event_calendar_uri)
                                            <a href="{{$org->event_calendar_uri }}" rel="external">
                                                Events Site
                                                <i class="fa fa-external-link"></i>
                                            </a>
                                        @endif
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
