@extends('layouts.app')

@section('title', 'Calendar')

@section('content')
    <div class="container">
        {!! $html !!}

        <div class="clearfix"></div>
        <p>
            <small>This data is sourced from <a href="https://data.openupstate.org" target="_blank">a community-curated REST API</a>. To contribute or use the API connect with <a href="https://codeforgreenville.org">Code
                    For
                    Greenville.</a></small>
    </p>
    </div>
@endsection


@section('js')
    {!! $js !!}
@endsection
