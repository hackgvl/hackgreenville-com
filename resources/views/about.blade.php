@extends('layouts.app')

@section('title', 'About HackGreenville')
@section('description', 'Discover the origins, mission, vision, and values of the HackGreenville community.')

@section('content')
    <div id="about-us" class="container max-w-7xl mx-auto px-4">
        <h1 class="title-heading">About HackGreenville</h1>

        <h2 class="font-weight-bold text-2xl mt-8 mb-4">History</h2>
        <p class="lead-text">Our journey began as a humble <a href="/join-slack" class="highlight-link">Slack chat
                group</a> back in March 2015, thanks to the efforts of Andrew Orr
            <a class="user-mention" href="https://github.com/Soulfire86" rel="nofollow">(@Soulfire86)</a>
            and Dave Brothers (<a class="user-mention" href="https://github.com/davebrothers" rel="nofollow">@davebrothers</a>).</p>
        <p class="lead-text">In Nov. 2023, HackGreenville established a relationship with <a href="https://refactorgvl.com/" target="refactorgvl" class="highlight-link">RefactorGVL</a>, a local 501(c)(3) non-profit, to further their mission of elevating the tech community in the Upstate. This collaboration provides infrastructure, fiscal sponsorship, and other support to HackGreenville and the local tech workforce.</p>

        <h2 class="font-weight-bold text-2xl mt-8 mb-4">Initiatives</h2>
        <h3 class="text-xl font-semibold mb-2">HG Slack</h3>
        <p class="lead-text">Our <a href="/join-slack" class="highlight-link">Slack community</a> continues to be a
            vibrant hub for insightful conversations and discovery.
        </p>
        <h3 class="text-xl font-semibold mb-2">HG Nights</h3>
        <p class="lead-text">Since 2023, we host a quarterly-ish <a class="highlight-link" href="/hg-nights"><em>HackGreenville Nights</em></a> gathering with socialing, good food, and optional short talks to bridge the gap between our monthly meetups and our annual conferences.</p>
        <h3 class="text-xl font-semibold mb-2">HG Labs</h3>
        <p class="lead-text">
           This website, the <em><a href="https://github.com/hackgvl/hackgreenville-com/blob/develop/ORGS_API.md" class="highlight-link">Organizations API</a></em>, and <em><a href="https://github.com/hackgvl/hackgreenville-com/blob/develop/EVENTS_API.md" class="highlight-link">Events API</a></em>,
           are developed and supported by <a href="/labs" class="highlight-link">HackGreenville Labs</a> to promote discovery of our local, non-commercial
           <a href="/orgs" class="highlight-link">orgs</a> and <a href="/events" class="highlight-link">events</a>.
        </p>
        <p class="lead-text">The HackGreenville.com website was initially brought to life by the <em>SC Codes</em>
            pilot program, and was later nurtured and expanded by <em>Code For Greenville</em>.
        </p>
        <h2 class="font-weight-bold text-2xl mt-8 mb-4">Why HG?</h2>
        <p class="lead-text">Here's a snapshot of our community's purpose, mission, vision, culture, and principles:</p>
        <h3 class="text-xl font-semibold mb-2">Our Purpose</h3>
        <p class="lead-text">We are here to nurture personal growth among the vibrant community of
            "hackers" in Greenville, SC and the surrounding Upstate area.</p>
        <h3 class="text-xl font-semibold mb-2">Our Mission</h3>
        <p class="lead-text">Through our <a href="/join-slack" class="highlight-link">online community</a> and <a
                href="/" class="highlight-link">discovery
                tools</a>, we aim to spotlight local, non-commercial tech opportunities for learning, sharing, and
            connecting.</p>
        <h3 class="text-xl font-semibold mb-2">Our Vision</h3>
        <p class="lead-text">We aspire to be the first point of call -the <code class="highlight-code">"GO TO"</code>- for
            tech enthusiasts exploring the area's vibrant "hacker" culture and opportunities.</p>
        <h3 class="text-xl font-semibold mb-2">Our Culture and Guiding Principles</h3>
        <ul class="values-list">
            <li>We expect everyone to abide by the <a href="/code-of-conduct" class="highlight-link">Code of Conduct</a> within our Slack and at in-person events.</li>
            <li>We exist to nurture personal growth, not to bring people down.
                Constructive debate, even "Tabs" or "Spaces", is welcome, but please do not harrass or provoke.
            </li>
            <li>We welcome all hackers, makers, and tinker types. Our community extends beyond just software.</li>
            <li>Be respectful. Egos and biases have no place here.</li>
            <li>Pay it forward. Remember, your knowledge comes from others.</li>
            <li>Give more than you take. This isn't a community for selfish pursuits.</li>
        </ul>
        <h2 class="font-weight-bold text-2xl mt-8 mb-4">Committee</h2>
        <p class="lead-text">Our committee members help with strategic priorities and decisions across HackGreenville's initiatives.</p>
        <ul class="values-list">
            <li><a href="https://www.linkedin.com/in/bogdan-kharchenko/" class="highlight-link">Bogdan Kharchenko</a></li>
            <li>Eric Anderson</li>
            <li><a href="https://www.linkedin.com/in/pamelawoodbrowne/" class="highlight-link">Pamela Wood Browne</a></li>
        </ul>
    </div>
@endsection