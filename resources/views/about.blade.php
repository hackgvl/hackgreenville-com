@extends('layouts.app')

@section('title', 'About HackGreenville')
@section('description', 'Discover the origins, mission, vision, and values of the HackGreenville community.')

@section('content')
    <div id="about-us" class="container">
        <h1 class="title-heading">About HackGreenville</h1>

        <h2>Our History</h2>
        <p class="lead-text">Our journey began as a humble <a href="/join-slack" class="highlight-link">Slack chat
                group</a> back in March 2015, thanks to the efforts of Andrew Orr
            <a class="user-mention" href="https://github.com/Soulfire86">(@Soulfire86)</a>
            and Dave Brothers (<a class="user-mention" href="https://github.com/davebrothers">@davebrothers</a>).</p>
        <p class="lead-text">In Nov. 2023, HackGreenville established a collaborative relationship with <a href="https://refactorgvl.com/" target="refactorgvl">RefactorGVL</a>, a local 501(c)(3) non-profit, to further their mission of elevating the tech community in the Upstate. This collaboration provides infrastructure, fiscal sponsorship, and other support services to HackGreenville as it serves the local workforce.</p>

        <p class="lead-text">Our <a href="/join-slack" class="highlight-link">Slack community</a> continues to be a
            resilient and vibrant hub for numerous insightful conversations, standing the test of time.
        </p>
        <h2>HackGreenville Nights</h2>
        <p>Since 2023, we host a quarterly-ish <a href="https://hackgreenville.com/hg-nights"><em>HackGreenville Nights</em></a> gathering with socialing, good food, and optional short talks to bridge the gap between our monthly meetups and our annual conferences.</p>
        <h2>HackGreenville Labs</h2>
        <p>
           This website and the related _Organizations_ and _Events_ APIs
           are now <a href="https://github.com/hackgvl/hackgreenville-com" class="highlight-link">
           developed and supported by HackGreenville Labs</a> to promote local meetup groups and events.
        </p>
        <p class="lead-text">The HackGreenville.com website was initially brought to life by the _SC Codes_
            pilot program and was later nurtured and expanded by _Code For Greenville_.
        </p>
        <p class="lead-text">Here's a snapshot of our community's purpose, mission, vision, and culture:</p>
        <h2 class="section-heading">Our Purpose</h2>
        <p class="lead-text">We are here to nurture personal growth among the vibrant community of
            "hackers" in Greenville, SC and the surrounding Upstate area.</p>
        <h2 class="section-heading">Our Mission</h2>
        <p class="lead-text">Through our <a href="/join-slack" class="highlight-link">online community</a> and <a
                href="/" class="highlight-link">discovery
                tools</a>, we aim to spotlight local, non-commercial tech opportunities for learning, sharing, and
            connecting.</p>
        <h2 class="section-heading">Our Vision</h2>
        <p class="lead-text">We aspire to be the first point of call -the <code class="highlight-code">GO TO</code>- for
            tech enthusiasts exploring the area's vibrant "hacker" culture and opportunities.</p>
        <h2 class="section-heading">Our Culture and Guiding Principles</h2>
        <ul class="values-list">
            <li>We expect everyone to abide by the <a href="{{route('code-of-conduct')}}">Code of Conduct</a> within our Slack and at in-person events.</li>
            <li>We exist to nurture personal growth, not to bring people down.
                Constructive debate, even "Tabs" or "Spaces", is welcome, but please do not harrass or provoke.
            </li>
            <li>We welcome all hackers, makers, and tinker types. Our community extends beyond just software.</li>
            <li>Be respectful. Egos and biases have no place here.</li>
            <li>Pay it forward. Remember, your knowledge comes from others.</li>
            <li>Give more than you take. This isn't a community for selfish pursuits.</li>
        </ul>
    </div>
@endsection
