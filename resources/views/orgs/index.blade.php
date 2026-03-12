@extends('layouts.app')

@section('title', 'Tech Organizations near Greenville, SC')
@section('description', 'A list of tech meetups, code schools, tech conferences / hack-a-thons near Greenville, SC, including inactive organizations.')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">

        <h1 class="text-3xl font-bold mb-8">Local Tech Organizations</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-start">
            @foreach ($activeOrgs as $organizations)
                @if($organizations->first()->category->isInactive())
                    @continue
                @endif
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-4 py-2.5 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">{{$organizations->first()->category->label}}</h2>
                        <span class="text-xs text-gray-400 font-medium">{{$organizations->count()}}</span>
                    </div>
                    <div>
                        @foreach($organizations as $org)
                            <div class="px-4 py-2 border-b border-gray-100 last:border-b-0 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-center gap-2">
                                    <a href="{{ route('orgs.show', $org) }}" title="{{ $org->title }}" class="text-gray-800 hover:text-primary text-sm truncate block">
                                        {{ $org->title }}
                                    </a>
                                    @if($org->event_calendar_uri)
                                        <a href="{{$org->event_calendar_uri }}" rel="external" class="text-gray-300 hover:text-primary transition-colors shrink-0" title="Events site">
                                            <x-lucide-external-link class="w-3 h-3 inline"/>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Inactive orgs: collapsed --}}
        @foreach ($activeOrgs as $organizations)
            @if($organizations->first()->category->isInactive())
                <details class="mt-8 border border-gray-200 rounded-lg">
                    <summary class="px-4 py-3 cursor-pointer select-none text-sm text-gray-400 hover:text-gray-600 transition-colors">
                        <span class="font-semibold uppercase tracking-wide">{{$organizations->first()->category->label}}</span>
                        <span class="ml-2 text-xs font-medium">({{$organizations->count()}})</span>
                    </summary>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-4 px-4 py-3 border-t border-gray-100">
                        @foreach($organizations as $org)
                            <a href="{{ route('orgs.show', $org) }}" title="{{ $org->title }}" class="text-gray-400 hover:text-gray-600 text-sm truncate block py-1">
                                {{ $org->title }}
                            </a>
                        @endforeach
                    </div>
                </details>
            @endif
        @endforeach


    </div>

@endsection
