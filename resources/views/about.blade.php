@extends('layouts.app')

@section('title', 'About HackGreenville')
@section('description', 'Discover the origins, mission, vision, and values of the HackGreenville community.')

@section('content')
    <div id="about-us" class="max-w-4xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">About HackGreenville</h1>

        <h2 class="font-bold text-2xl mt-8 mb-4">History</h2>
        <p class="text-base mb-4 leading-relaxed">Our journey began as a humble <a href="/join-slack" class="text-primary underline hover:text-blue-600">Slack chat
                group</a> back in March 2015, thanks to the efforts of Andrew Orr
            <a class="text-gray-700 hover:text-gray-900 underline" href="https://github.com/Soulfire86" rel="nofollow">(@Soulfire86)</a>
            and Dave Brothers (<a class="text-gray-700 hover:text-gray-900 underline" href="https://github.com/davebrothers" rel="nofollow">@davebrothers</a>).</p>
        <p class="text-base mb-4 leading-relaxed">In Nov. 2023, HackGreenville established a relationship with <a href="https://refactorgvl.com/" target="refactorgvl" class="text-primary underline hover:text-blue-600">RefactorGVL</a>, a local 501(c)(3) non-profit, to further their mission of elevating the tech community in the Upstate. This collaboration provides infrastructure, fiscal sponsorship, and other support to HackGreenville and the local tech workforce.</p>

        <h2 class="font-bold text-2xl mt-8 mb-4">Initiatives</h2>
        <h3 class="text-xl font-semibold mb-2">HG Slack</h3>
        <p class="text-base mb-4 leading-relaxed">Our <a href="/join-slack" class="text-primary underline hover:text-blue-600">Slack community</a> continues to be a
            daily hub for insightful conversations and discovery.
        </p>
        <h3 class="text-xl font-semibold mb-2">HG Nights</h3>
        <p class="text-base mb-4 leading-relaxed">Since 2023, we host a quarterly-ish <a class="text-primary underline hover:text-blue-600" href="/hg-nights"><em>HackGreenville Nights</em></a> gathering with socializing, good food, and optional short talks to bridge the gap between our monthly meetups and our annual conferences.</p>
        <h3 class="text-xl font-semibold mb-2">HG Labs</h3>
        <p class="text-base mb-4 leading-relaxed">
           This website, the <em><a href="https://github.com/hackgvl/hackgreenville-com/blob/develop/ORGS_API.md" class="text-primary underline hover:text-blue-600">Organizations API</a></em>, and <em><a href="https://github.com/hackgvl/hackgreenville-com/blob/develop/EVENTS_API.md" class="text-primary underline hover:text-blue-600">Events API</a></em>,
           are developed and supported by <a href="/labs" class="text-primary underline hover:text-blue-600">HackGreenville Labs</a> to promote discovery of our local, non-commercial
           <a href="/orgs" class="text-primary underline hover:text-blue-600">orgs</a> and <a href="/events" class="text-primary underline hover:text-blue-600">events</a>.
        </p>
        <p class="text-base mb-4 leading-relaxed">The HackGreenville.com website was initially brought to life by the <em>SC Codes</em>
            pilot program, and was later nurtured and expanded by <em>Code For Greenville</em>.
        </p>
        <h2 class="font-bold text-2xl mt-8 mb-4">Why HG?</h2>
        <p class="text-base mb-4 leading-relaxed">Here's a snapshot of our community's purpose, mission, vision, culture, and principles:</p>
        <h3 class="text-xl font-semibold mb-2">Our Purpose</h3>
        <p class="text-base mb-4 leading-relaxed">We are here to nurture personal growth among the vibrant community of
            "hackers" in Greenville, SC and the surrounding Upstate area.</p>
        <h3 class="text-xl font-semibold mb-2">Our Mission</h3>
        <p class="text-base mb-4 leading-relaxed">Through our <a href="/join-slack" class="text-primary underline hover:text-blue-600">online community</a> and <a
                href="/" class="text-primary underline hover:text-blue-600">discovery
                tools</a>, we aim to spotlight local, non-commercial tech opportunities for learning, sharing, and
            connecting.</p>
        <h3 class="text-xl font-semibold mb-2">Our Vision</h3>
        <p class="text-base mb-4 leading-relaxed">We aspire to be the first point of call -the <code class="bg-gray-100 px-2 py-1 rounded font-mono text-sm">"GO TO"</code>- for
            tech enthusiasts exploring the area's vibrant "hacker" culture and opportunities.</p>
        <h3 class="text-xl font-semibold mb-2">Our Culture and Guiding Principles</h3>
        <ul class="list-disc pl-8 space-y-2">
            <li class="text-base leading-relaxed">We expect everyone to abide by the <a href="/code-of-conduct" class="text-primary underline hover:text-blue-600">Code of Conduct</a> within our Slack and at in-person events.</li>
            <li class="text-base leading-relaxed">We exist to nurture personal growth, not to bring people down.
                Constructive debate, even "Tabs" or "Spaces", is welcome, but please do not harass or provoke.
            </li>
            <li class="text-base leading-relaxed">We welcome all hackers, makers, and tinker types. Our community extends beyond just software.</li>
            <li class="text-base leading-relaxed">Be respectful. Egos and biases have no place here.</li>
            <li class="text-base leading-relaxed">Pay it forward. Remember, your knowledge comes from others.</li>
            <li class="text-base leading-relaxed">Give more than you take. This isn't a community for selfish pursuits.</li>
        </ul>
        <h2 class="font-bold text-2xl mt-8 mb-4">Committee</h2>
        <p class="text-base mb-4 leading-relaxed">Our committee members help with strategic priorities and decisions across HackGreenville's initiatives.</p>
        <ul class="list-disc pl-8 space-y-2">
            <li class="text-base leading-relaxed"><a href="https://www.linkedin.com/in/bogdan-kharchenko/" class="text-primary underline hover:text-blue-600">Bogdan Kharchenko</a></li>
            <li class="text-base leading-relaxed">Eric Anderson</li>
            <li class="text-base leading-relaxed"><a href="https://www.linkedin.com/in/pamelawoodbrowne/" class="text-primary underline hover:text-blue-600">Pamela Wood Browne</a></li>
        </ul>
    </div>
@endsection
