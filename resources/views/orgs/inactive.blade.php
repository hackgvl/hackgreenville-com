@extends('layouts.app')

@section('title', 'Inactive Tech Organizations')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">

        <h1 class="text-4xl font-bold mb-8">Local Tech Organizations <span class="text-danger">(Inactive Groups)</span></h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($inactiveOrgs as $cat_inc => $category)
                <div class="bg-gray-100 rounded overflow-hidden">
                    <div class="bg-gray-200 px-4 py-3 flex justify-between items-center">
                        <h2 class="text-base font-semibold">{{$category[0]->category->label}}</h2>
                        <span class="bg-gray-300 text-gray-700 px-2.5 py-1 rounded text-sm font-semibold">{{$category->count()}}</span>
                    </div>
                    <div class="bg-white">
                        @foreach($category as $org)
                            <div class="px-4 py-2.5 border-b border-gray-200 last:border-b-0 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-start gap-2">
                                    <div class="flex-grow min-w-0">
                                        <a href="{{ route('orgs.show', $org) }}" title="{{ $org->title }}" class="text-gray-500 hover:underline text-sm truncate block">
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
            @empty
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <h3 class="text-gray-300 text-2xl mb-2">
                            YAY
                        </h3>
                        <h2 class="text-3xl font-bold text-gray-700 mb-2">No inactive groups at this time</h2>
                        <h3 class="text-gray-300 text-2xl">
                            Sorry to disappoint
                        </h3>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-8 text-sm text-gray-600">
            <ul class="list-disc pl-5 space-y-2">
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
