@extends('layouts.app')

@section('title', $org->title)
@section('description', 'Highlights of the '. $org->title . ' organization of '. $org->city . ', SC, including upcoming events, organizer, and history.')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div class="mb-3 md:mb-0">
                <h1 class="text-4xl md:text-5xl font-bold text-primary">
                    {{ $org->title }}
                </h1>
            </div>

            <div class="w-full md:w-auto">
                <a href="{{ route('calendar-feed.index', ['orgs' => $org->id]) }}" class="inline-flex items-center justify-center bg-primary text-white rounded-full px-6 py-3 shadow-sm hover:bg-blue-700 transition-colors w-full md:w-auto no-underline">
                    <i class="fa fa-calendar mr-2"></i>
                    Subscribe
                </a>
            </div>
        </div>

        <blockquote class="border-l-4 border-gray-300 pl-4 italic mb-6">
            {!! $org->description !!}
        </blockquote>

        <table class="w-full border-collapse">
            <tbody>
                @if($org->event_calendar_uri)
                    <tr class="border-b">
                        <th scope="row" class="text-left py-3 pr-4 font-medium">
                            Event Homepage
                        </th>
                        <td class="py-3">
                            <a href="{{ $org->event_calendar_uri }}" class="text-primary underline hover:text-blue-600">{{ $org->event_calendar_uri }}</a>
                        </td>
                    </tr>
                @endif
                @if($org->uri)
                    <tr class="border-b">
                        <th scope="row" class="text-left py-3 pr-4 font-medium">
                            Organization Homepage
                        </th>
                        <td class="py-3">
                            <a href="{{ $org->uri }}" class="text-primary underline hover:text-blue-600">{{ $org->uri }}</a>
                        </td>
                    </tr>
                @endif
                <tr class="border-b">
                    <th scope="row" class="text-left py-3 pr-4 font-medium">
                        City
                    </th>
                    <td class="py-3">
                        {{ $org->city }}
                    </td>
                </tr>
                <tr class="border-b">
                    <th scope="row" class="text-left py-3 pr-4 font-medium">
                        Focus Area
                    </th>
                    <td class="py-3">
                        {{ $org->focus_area }}
                    </td>
                </tr>
                <tr class="border-b">
                    <th scope="row" class="text-left py-3 pr-4 font-medium">
                        Contact Person
                    </th>
                    <td class="py-3">
                        {{ $org->primary_contact_person }}
                    </td>
                </tr>
                <tr class="border-b">
                    <th scope="row" class="text-left py-3 pr-4 font-medium">
                        Organization Type
                    </th>
                    <td class="py-3">
                        {{ $org->organization_type }}
                    </td>
                </tr>
                <tr class="border-b">
                    <th scope="row" class="text-left py-3 pr-4 font-medium">
                        Year Established
                    </th>
                    <td class="py-3">
                        {{ $org->established_at->year }}
                    </td>
                </tr>
                @if($org->inactive_at)
                    <tr class="border-b">
                        <th scope="row" class="text-left py-3 pr-4 font-medium">
                            Year Inactive
                        </th>
                        <td class="py-3">
                            {{ $org->inactive_at->year }}
                        </td>
                    </tr>
                @endif
                <tr class="border-b">
                    <th scope="row" class="text-left py-3 pr-4 font-medium">
                        Organization Status
                    </th>
                    <td class="py-3">
                        <div class="text-2xl">
                            @if($org->isActive())
                                <span class="inline-block bg-success text-white px-3 py-1 rounded text-base font-medium">Active</span>
                            @else
                                <span class="inline-block bg-blue-500 text-white px-3 py-1 rounded text-base font-medium">Inactive</span>
                            @endif
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        @if($org->events->isNotEmpty())
            <h2 class="text-3xl font-bold mt-8 mb-4">Upcoming Events</h2>
            @foreach($org->events as $event)
                @include('events._item', ['event' => $event])
            @endforeach
        @endif
    </div>
@endsection
