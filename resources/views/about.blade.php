@extends('layouts.app')

@section('title', 'About HackGreenville')
@section('description', 'About the founding, mission, vision, and purpose of the HackGreenville community.')

@section('content')
    <div id="about-us" class="container">
        <h1>About HackGreenville</h1>
        <p>HackGreenville was started as a <a href="/join-slack">Slack chat group</a> in March 2015 by Andrew Orr
            <a class="user-mention" href="https://github.com/Soulfire86">(@Soulfire86)</a>
            and Dave Brothers (<a class="user-mention" href="https://github.com/davebrothers">@davebrothers</a>).</p>
        <p>The <a href="/join-slack">Slack community</a> has stood the "test of time" and continues to be a daily, active hub for countless and varied
            conversations.</p>
        <p>The HackGreenville.com website was initially developed by the <a href="https://www.sccodes.org">SC Codes</a>
            pilot program and later adopted and expanded by <a href="https://codeforgreenville.org">Code
                For Greenville</a>. The site leverages <a href="https://data.openupstate.org">open data APIs developed by Code For Greenville</a> to promote local, related meetup groups and
            events.</p>
        <p>The following summarizes the overall community's purpose, mission, vision, and culture.</p>
        <h2>Purpose</h2>
        <p>HackGreenville exists to foster personal growth among the "hackers" of Greenville, SC and the surrounding area.</p>
        <h2>Mission</h2>
        <p>HackGreenville hosts an <a href="/join-slack">online community</a> and <a href="/">discovery
                tools</a> to promote local, non-commercial tech opportunities for learning, sharing, and connecting.</p>
        <h2>Vision</h2>
        <p>HackGreenville desires to become the <code>GO TO</code> (pun intended!) brand and resource for tech people discovering the area's "hacker" culture and opportunities.</p>
        <h2>Culture and Guiding Principles</h2>
        <ul>
            <li>We're open to all hackers, makers, and tinker types. We're not exclusive to software.</li>
            <li>Be nice. Keep egos and biases in check.</li>
            <li>Have fun. Trolling is not cool.</li>
            <li>Pay-it-forward. You learned most of what you know from others.</li>
            <li>Give more than you take. This is not a community for selfish pursuits.</li>
        </ul>
    </div>
@endsection
