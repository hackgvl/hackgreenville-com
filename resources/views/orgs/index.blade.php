@extends('layouts.app')

@section('title', 'Organizations')

@section('content')

    <h1>Local Tech Organizations</h1>

    <div class="row">
        <div class="col-md-6">
            @foreach ($activeOrgs as $cat_inc => $category)
                <ul class="list-group">
                    <li class="list-group-item list-group-item-primary">
                        <h2>{{$category[0]->category->label}}</h2>
                    </li>

                    @foreach($category as $org)
                        <li class="list-group-item">
                            <a data-toggle="collapse" data-target="#org-{{$org->cache['nid']}}" href="#org-{{$org->cache['nid']}}" class=""> {{ $org->title }}</a>
                            <div class="collapse" id="org-{{$org->cache['nid']}}">
                                <p>
                                    <a href="{{ $org->homepage}}" target="_blank">Homepage</a> |
                                    <a href="{{$org->event_calendar_homepage }}" target="_blank">Events Site</a>
                                </p>
                            </div>
                        </li>
                    @endforeach
                </ul>

                @if($cat_inc < count($activeOrgs))
                    <hr/>
                @endif
            @endforeach
        </div>

        <div class="col-md-6">
            <h2>Inactive Organizations</h2>

            <ul>
                @foreach ($inactiveOrgs as $org)
                    <li>
                        <a class="" href="{{ $org->homepage }}" target="_blank">{{ $org->title }}</a>
                        {{ $org->city }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <p>
        <small>
            This data is sourced from <a href="https://data.openupstate.org" target="_blank">a community-curated REST API</a>.
            To contribute or use the API connect with <a href="https://codeforgreenville.org" target="_blank">Code For Greenville.</a>
        </small>
    </p>

@endsection
