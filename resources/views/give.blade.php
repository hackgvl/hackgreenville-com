@extends('layouts.app')

@section('title', 'Hackgreenville - Give (WIP title)')
@section('description', "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna.")

@section('content')
    <div id="give-page">
        <div class="container my-5">
            <div class="row mb-5">
                <div class="w-100">
                    <div class="text-center">
                        <h3 class="screaming-hackgreenville-question">How do I contribute?</h3>

                        <p class="summary">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </p>
                    </div>
                </div>
            </div>

            <div id="donation-orgs" class="row organizations-group mb-5">
                <h4>Donate</h4>
                <div class="options-row w-100">
                    <div class="organization">
                        <h5>Develop Carolina</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </div>
                    <div class="organization">
                        <h5>RefactorGVL</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </div>
                    <div class="organization">
                        <h5>Synergy Mill</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </div>
                </div>
            </div>
            <div id="volunteer-orgs" class="row organizations-group">
                <h4>Volunteer</h4>
                <div class="options-row w-100">
                    <div class="organization">
                        <h5>Carolina Code Conference</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </div>
                    <div class="organization">
                        <h5>Synergy Mill</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </div>
                    <div class="organization">
                        <h5>Hack For Good</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </div>
                    <div class="organization">
                        <h5>HackGreenville Labs</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </div>
                    <div class="organization">
                        <h5>HackGreenville Nights</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
