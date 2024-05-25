@extends('layouts.app')

@section('title', $org->title)
@section('description', 'Highlights of the '. $org->title . ' organization of '. $org->city . ', SC, including upcoming events, organizer, and history.')

@section('content')
    <div class="container">
        <h1>
            {{ $org->title }}
        </h1>

        <blockquote>
            {!! $org->description !!}
        </blockquote>

        <table class="table">
            <tbody>
                @if($org->event_calendar_uri)
                    <tr>
                        <th scope="row">
                            Event Homepage
                        </th>
                        <td>
                            <a href="{{ $org->event_calendar_uri }}">{{ $org->event_calendar_uri }}</a>
                        </td>
                    </tr>
                @endif
                <tr>
                    <th scope="row">
                        City
                    </th>
                    <td>
                        {{ $org->city }}
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        Focus Area
                    </th>
                    <td>
                        {{ $org->focus_area }}
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        Contact Person
                    </th>
                    <td>
                        {{ $org->primary_contact_person }}
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        Organization Type
                    </th>
                    <td>
                        {{ $org->organization_type }}
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        Year Established
                    </th>
                    <td>
                        {{ $org->established_at->year }}
                    </td>
                </tr>
                @if($org->inactive_at)
                    <tr>
                        <th scope="row">
                            Year Inactive
                        </th>
                        <td>
                            {{ $org->inactive_at->year }}
                        </td>
                    </tr>
                @endif
                <tr>
                    <th scope="row">
                        Organization Status
                    </th>
                    <td>
                        <div style="font-size: 1.5em">
                            @if($org->isActive())
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-info">Inactive</span>
                            @endif
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        @if($org->events->isNotEmpty())
            <h2>Upcoming Events</h2>
            @foreach($org->events as $event)
                @include('events._item', ['event' => $event])
            @endforeach
        @endif
    </div>
@endsection
