@extends('layouts.page')

@section('title', 'Organizations')

@section('content')

        <h1>Local Tech Organizations</h1>

        @foreach ($activeOrgs as $groupKey => $group)

        <h2>{{$groupKey}}</h2>
        <ul>
            @foreach ($group as $org)
                <li>
                    <a data-toggle="collapse" data-target="#org-{{$org->nid}}" href="#org-{{$org->nid}}" class="btn-link btn-info"> {{ $org->title }}</a>
                    <div class="collapse" id="org-{{$org->nid}}">
                        <p>
                            <a href="{{ getOrgWebsite( $org ) }}">Homepage</a> |
                            <a href="{{$org->field_event_calendar_homepage }}">Events Site</a>
                        </p>
                    </div>
                </li>
            @endforeach
        </ul>
        @endforeach

        <h2>Inactive Organizations</h2>

        <ul>
            @foreach ($inactiveOrgs as $org)
                <li>
                    <a class="btn-link btn-info" href="{{ getOrgWebsite( $org ) }}">{{ $org->title }}</a>
                    {{ $org->field_city }}
                </li>
            @endforeach
        </ul>

        <p><small>This data is sourced from <a href="https://data.openupstate.org">a community-curated REST API</a>. To contribute or use the API connect with <a href="https://codeforgreenville.org">Code For Greenville.</a></small></p>

@endsection
