@extends('layouts.app')

@section('title', 'Tech Organizations near Greenville, SC')
@section('description', 'A list of tech meetups, code schools, tech conferences / hack-a-thons near Greenville, SC, including inactive organizations.')

@section('content')
    <div class="container max-w-7xl mx-auto px-4 py-8">

        <h1 class="text-4xl font-bold mb-8">Local Tech Organizations</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($activeOrgs as $organizations)
                <div class="bg-gray-100 rounded overflow-hidden">
                    <div class="bg-gray-200 px-4 py-3 flex justify-between items-center">
                        <h2 class="text-base font-semibold">{{$organizations->first()->category->label}}</h2>
                        <span class="bg-gray-300 text-gray-700 px-2.5 py-1 rounded text-sm font-semibold">{{$organizations->count()}}</span>
                    </div>
                    <div class="bg-white">
                        @foreach($organizations as $org)
                            <div class="px-4 py-2.5 border-b border-gray-200 last:border-b-0 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-start gap-2">
                                    <div class="flex-grow min-w-0">
                                        <a href="{{ route('orgs.show', $org) }}" title="{{ $org->title }}"
                                            @class([
                                                'text-gray-500 line-through' => $org->category->isInactive(),
                                                'text-primary hover:underline' => !$org->category->isInactive(),
                                                'text-sm truncate block'
                                            ])
                                        >
                                            {{ $org->title }}
                                        </a>
                                    </div>
                                    @if($org->event_calendar_uri)
                                        <div class="flex-shrink-0">
                                            <a href="{{$org->event_calendar_uri }}" rel="external" class="text-primary hover:underline text-xs whitespace-nowrap">
                                                Events Site<i class="fa fa-external-link ml-0.5 text-xs"></i>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 text-sm text-gray-600">
            <ul class="list-disc pl-5 space-y-2">
                <li>You can view <a href="{{ route('orgs.inactive') }}" class="text-primary hover:underline">inactive organizations here</a>.
                </li>
                <li>This data is sourced from <a href="https://data.openupstate.org" rel="external" class="text-primary hover:underline">a community-curated
                        REST API</a>.
                </li>
                <li>To contribute to this project, please connect with <a href="https://codeforgreenville.org"
                                                                          rel="external" class="text-primary hover:underline">HackGreenville Labs.</a></li>
                <li>To suggest an addition or update to the data, please submit a <a
                        href="https://data.openupstate.org/contact/suggestions" class="text-primary hover:underline">suggestion</a>.
                </li>
            </ul>
        </div>

    </div>

@endsection