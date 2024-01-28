@extends('layouts.app')

@section('title', 'Hackgreenville - Give (WIP title)')
@section('description', "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna.")

@section('content')
    <div id="give-page">
        <div class="container my-5">
            <div class="row">
                <div class="w-100">
                    <div class="text-center">
                        <h3 class="screaming-hackgreenville-question">How do I contribute?</h3>
                    </div>
                </div>
            </div>

            <div id="donation-orgs" class="row organizations-group">
                <h4>Donate</h4>
                <div class="options-row w-100">
                    <div class="organization">
                        <h5>Build Carolina</h5>
                        <dl>
                            <dt class="text-uppercase">Programs</dt>
                            <dd class="mb-0">Carolina Code School</dd>
                            <dd class="mb-0">SC Codes</dd>
                            <dd class="mb-0">Develop Carolina</dd>
                        </dl>
                        <p><a href="https://buildcarolina.org/build-carolina-giving"
                              target="build_carolina"
                              class="btn btn-info">Learn More</a></p>
                    </div>
                    <div class="organization">
                        <h5>RefactorGVL</h5>
                        <p>To donate for the HackGreenville initiative, please enter "HackGreenville" in the "Requested earmark" field on the donation form.</p>
                        <p><a href="https://refactorgvl.com/"
                              target="refactor_gvl"
                              class="btn btn-info">Learn More</a>
                        </p>
                    </div>
                    <div class="organization">
                        <h5>Synergy Mill</h5>
                        <p>Help keep Synergy Mill available for the makers, crafters, inventors, and small business owners in Greenville</p>
                        <p><a href="https://www.synergymill.com/donate"
                              target="synergy_mill_donate"
                              class="btn btn-info">Learn More</a></p>
                    </div>
                </div>
            </div>
            <div id="volunteer-orgs" class="row organizations-group">
                <h4>Volunteer</h4>
                <div class="options-row w-100">
                    <div class="organization">
                        <h5>Carolina Code Conference</h5>
                        <p>A welcoming and community-driven “polyglot” conference</p>
                        <p><a href="https://carolina.codes/"
                              target="carolina_code_conf"
                              class="btn btn-info">Learn More</a></p>
                    </div>
                    <div class="organization">
                        <h5>SC Codes</h5>
                        <p>Become a Mentor</p>
                        <p><a href="https://www.sccodes.org/mentorship/"
                              target="sccodes_mentorship"
                              class="btn btn-info">Learn More</a></p>
                    </div>
                    <div class="organization">
                        <h5>Synergy Mill</h5>
                        <dl>
                            <dt class="text-uppercase">Needs</dt>
                            <dd class="mb-0">Gardening</dd>
                            <dd class="mb-0">Shop Cleanup</dd>
                        </dl>
                        <p><a href="https://www.synergymill.com/"
                              target="synergy_mill"
                              class="btn btn-info">Learn More</a></p>
                    </div>
                    <div class="organization">
                        <h5>Code with the Carolinas</h5>
                        <p>A community of civic tech volunteers working together to improve wellbeing in North and South Carolina</p>
                        <p><a href="https://codewiththecarolinas.org/volunteer.html"
                              target="code_carolinas"
                              class="btn btn-info">Learn More</a></p>
                    </div>
                    <div class="organization">
                        <h5>HackGreenville Labs</h5>
                        <p>Mentoring, Inspiring and Innovating Local Tech</p>
                        <p><a href="{{ route('labs.index') }}"
                              class="btn btn-info">Learn More</a></p>
                    </div>
                    <div class="organization">
                        <h5>HackGreenville Nights</h5>
                        <p>A “Quarterly-ish” Gathering of Greenville's Tech Community</p>
                        <p><a href="{{ route('hg-nights.index') }}"
                              class="btn btn-info">Learn More</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
