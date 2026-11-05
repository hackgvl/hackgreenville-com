@extends('layouts.app')

@section('title', 'Open Map Data — Greenville, SC')
@section('description', 'Community-curated, open map layers for Greenville, SC. Browse GeoJSON data for parks, trails, breweries, and more — or contribute your own.')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">

        {{-- Header --}}
        <div class="mb-14">
            <h1 class="text-3xl sm:text-4xl font-bold mb-3">Open Map Data</h1>
            <p class="text-gray-600 text-base max-w-2xl leading-relaxed">
                Real-time, community-curated map layers for the Greenville, SC area.
                Each layer is powered by a public Google Spreadsheet that anyone can help maintain.
            </p>
        </div>

        {{-- How it works --}}
        <section class="mb-14">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="relative border border-gray-100 rounded-lg p-6 hover:border-primary/20 transition-colors overflow-hidden">
                    <x-lucide-table aria-hidden="true" class="absolute -right-3 -top-3 w-24 h-24 text-primary/[0.07]"/>
                    <h2 class="font-semibold text-gray-900 mb-2 relative">Spreadsheet-Powered</h2>
                    <p class="text-sm text-gray-600 leading-relaxed relative">
                        Data lives in public Google Sheets — no coding needed. Edits appear as GeoJSON automatically.
                    </p>
                </div>

                <div class="relative border border-gray-100 rounded-lg p-6 hover:border-success/30 transition-colors overflow-hidden">
                    <x-lucide-hand-helping aria-hidden="true" class="absolute -right-3 -top-3 w-24 h-24 text-success/[0.12]"/>
                    <h2 class="font-semibold text-gray-900 mb-2 relative">Community-Curated</h2>
                    <p class="text-sm text-gray-600 leading-relaxed relative">
                        Wikipedia-style — anyone can request edit access to a spreadsheet and help keep data accurate.
                    </p>
                </div>

                <div class="relative border border-gray-100 rounded-lg p-6 hover:border-warning/30 transition-colors overflow-hidden">
                    <x-lucide-code aria-hidden="true" class="absolute -right-3 -top-3 w-24 h-24 text-warning/[0.07]"/>
                    <h2 class="font-semibold text-gray-900 mb-2 relative">Developer-Friendly</h2>
                    <p class="text-sm text-gray-600 leading-relaxed relative">
                        Every layer has a GeoJSON endpoint you can plug into Leaflet, Google Maps, or any mapping tool.
                    </p>
                </div>

            </div>
        </section>

        {{-- How to contribute --}}
        <section class="mb-14 bg-gray-50 rounded-lg p-6 sm:p-8">
            <h2 class="text-lg font-bold mb-3">How to Contribute</h2>
            <p class="text-sm text-gray-700 leading-relaxed mb-2">
                The easiest way to contribute is to leave suggestions directly in the Google Spreadsheet:
            </p>
            <ol class="list-decimal list-inside text-sm text-gray-700 space-y-2 leading-relaxed mb-4">
                <li>Find a layer below and click <strong>Edit Spreadsheet</strong>.</li>
                <li>Add comments or suggestions directly in the sheet — no special permissions needed.</li>
            </ol>
            <p class="text-sm text-gray-600 leading-relaxed mb-2">
                <strong>Want to become an active maintainer?</strong> Request edit access from the Google Sheets menu
                so you can add and update rows directly. Each row is a point on the map with a Latitude, Longitude, and Title.
                Changes sync automatically to the GeoJSON endpoint.
            </p>
            <p class="text-sm text-gray-500 mt-3">
                You can also reach out in the
                <a href="/join-slack" class="text-primary underline hover:text-blue-600">#hg-labs channel</a>
                on the HackGreenville Slack.
            </p>
        </section>

        {{-- Layer listing --}}
        <section>
            <div class="flex items-center gap-3 mb-6">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest whitespace-nowrap">Map Layers</h2>
                <div class="h-px bg-gray-200 flex-1"></div>
                <span class="text-xs text-gray-300 font-medium tabular-nums">{{ $layers->count() }}</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($layers as $layer)
                    <div class="border border-gray-100 rounded-lg p-4 hover:border-gray-200 transition-colors">
                        <h3 class="font-semibold text-gray-900 text-sm mb-1">{{ $layer->title }}</h3>

                        @if ($layer->description)
                            <p class="text-xs text-gray-500 leading-relaxed mb-3 line-clamp-2">{{ $layer->description }}</p>
                        @endif

                        <div class="flex flex-wrap gap-2">
                            @if ($layer->contribute_link)
                                <a href="{{ $layer->contribute_link }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center gap-1 text-xs text-primary no-underline hover:underline">
                                    <x-lucide-pencil aria-hidden="true" class="w-3 h-3"/>
                                    Edit Spreadsheet
                                </a>
                            @endif

                            <a href="{{ route('api.v1.map-layers.geojson', $layer) }}"
                               class="inline-flex items-center gap-1 text-xs text-gray-500 no-underline hover:underline">
                                <x-lucide-braces aria-hidden="true" class="w-3 h-3"/>
                                GeoJSON
                            </a>

                            @if ($layer->raw_data_link)
                                <a href="{{ $layer->raw_data_link }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center gap-1 text-xs text-gray-500 no-underline hover:underline">
                                    <x-lucide-download aria-hidden="true" class="w-3 h-3"/>
                                    CSV
                                </a>
                            @endif
                        </div>

                        @if ($layer->maintainers && count($layer->maintainers))
                            <p class="text-xs text-gray-400 mt-2">
                                Maintained by {{ collect($layer->maintainers)->pluck('name')->join(', ') }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Developer section --}}
        <section class="mt-14 border-t border-gray-100 pt-10">
            <h2 class="text-lg font-bold mb-3">For Developers</h2>
            <p class="text-sm text-gray-600 leading-relaxed mb-4 max-w-2xl">
                All map layers are available through the HackGreenville REST API.
                Fetch the full list or grab individual GeoJSON files to use in your own apps.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="/docs/api" class="inline-flex items-center gap-2 bg-gray-900 text-white px-5 py-2.5 rounded-lg text-sm font-semibold no-underline hover:bg-gray-800 transition-colors">
                    <x-lucide-book-open aria-hidden="true" class="w-4 h-4"/>
                    API Docs
                </a>
                <a href="https://hackgvl.github.io/open-map-data-multi-layers-demo/" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 border border-gray-200 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-semibold no-underline hover:border-gray-300 transition-colors">
                    <x-lucide-map aria-hidden="true" class="w-4 h-4"/>
                    Multi-Layer Demo
                    <x-lucide-external-link aria-hidden="true" class="w-3 h-3"/>
                </a>
            </div>
        </section>

    </div>
@endsection
