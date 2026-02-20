@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold mb-8 text-center">HackGreenville Style Guide</h1>

        <section class="pb-8 mb-8 border-b border-gray-200">
            <h2 class="text-2xl font-bold mb-4">Voice and Tone</h2>
            <p class="text-gray-700 leading-relaxed">
                The voice and tone of HackGreenville are inclusive, encouraging, and community-focused. The content aims
                to empower members and foster personal growth through sharing and promoting local tech opportunities. There
                is a strong focus on participation and contributions to the community. The language is straightforward and
                conversational, with an energetic tone that motivates members to "Build Stuff, Meet People, and Do Cool
                Things".
            </p>
        </section>

        <section class="pb-8 mb-8 border-b border-gray-200">
            <h2 class="text-2xl font-bold mb-6 text-center">Colors</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-xl font-semibold mb-4">Background Colors</h3>
                    <ul class="space-y-3">
                        @php
                            $colors = [
                                '#ffffff',
                                '#201748',
                                '#ffa300',
                                '#201647',
                                '#c0c0c0',
                                '#f4f4f4',
                                '#006341',
                            ];
                        @endphp

                        @foreach($colors as $color)
                            <li class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded border border-gray-300 shadow-sm transition-transform hover:scale-110" 
                                     style="background-color: {{$color}};"></div>
                                <span class="font-mono text-sm">HEX {{$color}}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-4">Text Colors</h3>
                    <ul class="space-y-3">
                        @php
                            $font_colors = [
                                '#222222',
                                '#201647',
                                '#ffffff',
                                '#000000',
                                '#202020',
                                '#00704a',
                                '#6e7272',
                            ];
                        @endphp

                        @foreach($font_colors as $color)
                            <li class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded border border-gray-300 shadow-sm transition-transform hover:scale-110" 
                                     style="background-color: {{$color}};"></div>
                                <span class="font-mono text-sm">HEX {{$color}}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>


        <section class="pb-8 mb-8 border-b border-gray-200">
            <h2 class="text-2xl font-bold mb-4">Typography</h2>
            <p class="text-gray-700 mb-6">The website uses the following font styles:</p>
            <div class="space-y-6">
                <div class="p-4 bg-gray-50 rounded-lg transition-all hover:bg-gray-100">
                    <p class="transition-all hover:scale-105"
                       style="font-family: 'Lato',serif; font-size: 21px; line-height: 29px; color: #00704a;">
                        Lato, normal, 21px, 29px, #00704a
                    </p>
                </div>

                <div class="p-4 bg-gray-50 rounded-lg transition-all hover:bg-gray-100">
                    <p class="transition-all hover:scale-105"
                       style="font-family: 'Lato',serif; font-size: 27px; line-height: 32px; color: #006341;">
                        Lato, normal, 27px, 32px, #006341
                    </p>
                </div>

                <div class="p-4 bg-gray-50 rounded-lg transition-all hover:bg-gray-100">
                    <p class="transition-all hover:scale-105"
                       style="font-family: 'sans-serif',serif; font-size: 16px; line-height: 22px; color: #222222;">
                        Sans Serif, normal, 16px, 22px, #222222
                    </p>
                </div>
            </div>
        </section>

        <section>
            <div class="bg-gray-100 rounded-lg p-6 text-sm text-gray-700 leading-relaxed space-y-4">
                <p>
                    Please remember that this style guide should be considered a living document.
                    It is subject to change and should evolve as the HackGreenville brand,
                    and its community continues to grow and evolve.
                </p>

                <p>
                    To maintain consistency, always refer to this guide when creating content for HackGreenville.
                    If there are any uncertainties or if you're creating something that the guide doesn't cover,
                    please seek advice from the design team or relevant decision-makers.
                </p>
            </div>
        </section>
    </div>
@endsection
