@extends('layouts.app')

@section('title', 'Tech Organizations near Greenville, SC')
@section('description', 'A list of tech meetups, code schools, tech conferences / hack-a-thons near Greenville, SC, including inactive organizations.')

@section('content')
    <div class="container max-w-7xl mx-auto px-4">

        <h1 class="text-4xl font-bold mb-6">Local Tech Organizations</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($activeOrgs as $organizations)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-primary text-white p-4">
                        <h2 class="text-xl font-semibold flex justify-between items-center">
                            <span>{{$organizations->first()->category->label}}</span>
                            <span class="text-lg">{{$organizations->count()}}</span>
                        </h2>
                    </div>
                    <ul class="divide-y divide-gray-200">
                        @foreach($organizations as $org)
                            <li class="p-4">
                                <div class="flex flex-col md:flex-row justify-between">
                                    <div class="mb-2 md:mb-0">
                                        <a href="{{ route('orgs.show', $org) }}" title="Homepage"
                                            @class([
                                                'text-gray-500 line-through' => $org->category->isInactive(),
                                                'text-primary hover:text-blue-600 underline' => !$org->category->isInactive()
                                            ])
                                        >
                                            {{ $org->title }}
                                        </a>
                                    </div>
                                    <div class="text-right">
                                        @if($org->event_calendar_uri)
                                            <a href="{{$org->event_calendar_uri }}" rel="external" class="text-primary hover:text-blue-600 underline">
                                                Events Site
                                                <i class="fa fa-external-link ml-1"></i>
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

        <ul class="mt-8 list-disc pl-5 space-y-2">
            <li>You can view <a href="{{ route('orgs.inactive') }}" class="text-primary hover:text-blue-600 underline">inactive organizations here</a>.
            </li>
            <li>This data is sourced from <a href="https://data.openupstate.org" rel="external" class="text-primary hover:text-blue-600 underline">a community-curated
                    REST API</a>.
            </li>
            <li>To contribute to this project, please connect with <a href="https://codeforgreenville.org"
                                                                      rel="external" class="text-primary hover:text-blue-600 underline">HackGreenville Labs.</a></li>
            <li>To suggest an addition or update to the data, please submit a <a
                    href="https://data.openupstate.org/contact/suggestions" class="text-primary hover:text-blue-600 underline">suggestion</a>.
            </li>
        </ul>

    </div>

@endsection