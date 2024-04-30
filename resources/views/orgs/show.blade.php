@extends('layouts.app')

@section('title', $org->title)

@section('content')
	<div class="container">
		<h1>
			{{ $org->title }}
		</h1>

		<blockquote>
			{{ $org->description }}
		</blockquote>

        <ul>
            <li>
                <strong>Event Homepage</strong>: <a href="{{ $org->event_calendar_uri }}">{{ $org->event_calendar_uri }}</a>
            </li>
            <li>
                <strong>City</strong>: {{ $org->city }}
            </li>
            <li>
                <strong>Focus Area</strong>: {{ $org->focus_area }}
            </li>
            <li>
                <strong>Contact Person</strong>: {{ $org->primary_contact_person }}
            </li>
            <li>
                <strong>Organization Type</strong>: {{ $org->organization_type }}
            </li>
            <li>
                <strong>Year Established</strong>: {{ $org->established_at->year }}
            </li>
        </ul>

		<div style="font-size: 1.5em">
			@if($org->isActive())
				<span class="badge badge-primary">Active</span>
			@else
				<span class="badge badge-info">In Active</span>
			@endif
        </div>

    </div>
@endsection
