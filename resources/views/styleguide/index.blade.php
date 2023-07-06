@extends('layouts.app')

@section('head')
    <style>
        .color-sample-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            list-style-type: none;
            flex-direction: column;
        }

        .color-sample-wrapper {
            display: flex;
            align-items: center;
        }

        .color-sample {
            width: 50px;
            height: 50px;
            display: inline-block;
            transition: transform 0.3s ease;
            margin-right: 10px;
            border: 1px solid black;
        }

        .color-sample:hover {
            transform: scale(1.2);
        }

        .font-sample {
            transition: font-size 0.3s ease;
        }

        .font-sample:hover {
            font-size: 1.2em;
        }

        .style-guide.container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .style-guide section {
            width: 95%;
            border-bottom: 1px solid rgba(233, 215, 215, 0.51); /* change the color as needed */
            padding-bottom: 20px; /* optional, add space below the content */
            margin-bottom: 20px; /* optional, add space below the border */
        }
    </style>
@endsection

@section('content')
    <div class="style-guide container">
        <h1>HackGreenville Style Guide</h1>

        <section>
            <h2>Voice and Tone</h2>
            <p>The voice and tone of HackGreenville are inclusive, encouraging, and community-focused. The content aims
                to
                empower members and foster personal growth through sharing and promoting local tech opportunities. There
                is
                a strong focus on participation and contributions to the community. The language is straightforward and
                conversational, with an energetic tone that motivates members to "Build Stuff, Meet People, and Do Cool
                Things".</p>
        </section>

        <section>
            <div class="card">
                <div class="card-header">
                    <div class="col-md-12 text-center">
                        <h2>Colors</h2>
                    </div>
                </div>
                <div class="bard-body p-3">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Background Colors</h3>
                            <ul class="color-sample-list">
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
                                    <li class="color-sample-wrapper">
                                        <div class="color-sample" style="background-color: {{$color}};"></div>
                                        HEX {{$color}}
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h3>Text Colors</h3>
                            <ul class="color-sample-list">
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
                                    <li class="color-sample-wrapper">
                                        <div class="color-sample" style="background-color: {{$color}};"></div>
                                        HEX {{$color}}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section>
            <div class="card">
                <div class="card-header">
                    <h2>Typography</h2>
                    <p>The website uses the following font styles:</p>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li>
                            <p class="font-sample"
                               style="font-family: 'Lato',serif; font-size: 21px; line-height: 29px;">
                                Lato,
                                normal,
                                21px, 29px, #00704a
                            </p>
                        </li>

                        <li>
                            <p class="font-sample"
                               style="font-family: 'Lato',serif; font-size: 27px; line-height: 32px;">
                                Lato,
                                normal,
                                27px, 32px, #006341
                            </p>
                        </li>

                        <li>
                            <p class="font-sample"
                               style="font-family: 'sans-serif',serif; font-size: 16px; line-height: 22px;">
                                Sans Serif,
                                normal,
                                16px, 22px, #222222
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <section>
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
        </section>
    </div>
@endsection
