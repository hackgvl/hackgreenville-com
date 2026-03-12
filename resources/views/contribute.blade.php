@extends('layouts.app')

@section('title', 'Contribute Around Greenville, SC')
@section('description', "Opportunties to sponsor, volunteer, and donate with Upstate, SC tech, maker, and tinker non-profits or open-source projects.")

@section('content')
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="mb-12">
                <div class="text-left">
                    <h1 class="text-5xl font-bold mb-6">Contribute</h1>
                    <p class="text-xl text-gray-700 mb-8 max-w-4xl">Support the local organizations making things happen for our local tech workforce, makers, and tinkerers.</p>
                    <ul class="text-left max-w-3xl space-y-3 text-lg">
                        <li class="flex items-start"><span class="mr-2">•</span><span><strong class="font-bold">Volunteer</strong> your time or resources to a project or program</span></li>
                        <li class="flex items-start"><span class="mr-2">•</span><span>Connect with <a href="/join-slack" class="text-primary hover:underline"><em>#community-organizers</em></a> and get the spotlight by <strong class="font-bold">sponsoring</strong> an event or initiative</span></li>
                        <li class="flex items-start"><span class="mr-2">•</span><span><strong class="font-bold">Donate</strong> to our local non-profits</span></li>
                    </ul>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="bg-primary text-white p-4">
                            <h2 class="text-2xl font-bold text-center">Volunteer</h2>
                        </div>
                        <ul class="divide-y divide-gray-200">
                            <li class="p-4 hover:bg-gray-50 transition-colors"><a href="/orgs/agile-learning-institute" class="text-primary hover:underline block">Agile Learning Institute</a></li>
                            <li class="p-4 hover:bg-gray-50 transition-colors"><a href="/orgs/carolina-code-conf" class="text-primary hover:underline block">Carolina Code Conference</a></li>
                            <li class="p-4 hover:bg-gray-50 transition-colors"><a href="/orgs/code-with-the-carolinas" class="text-primary hover:underline block">Code with the Carolinas</a></li>
                            <li class="p-4 hover:bg-gray-50 transition-colors"><a href="/labs" class="text-primary hover:underline block">HackGreenville Labs</a></li>
                            <li class="p-4 hover:bg-gray-50 transition-colors"><a href="/hg-nights" class="text-primary hover:underline block">HackGreenville Nights</a></li>
                            <li class="p-4 hover:bg-gray-50 transition-colors"><a href="/orgs/synergy-mill" class="text-primary hover:underline block">Synergy Mill</a></li>
                        </ul>
                    </div>
                </div>

                <div>
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="bg-primary text-white p-4">
                            <h2 class="text-2xl font-bold text-center">Sponsor</h2>
                        </div>
                        <ul class="divide-y divide-gray-200">
                            <li class="p-4 hover:bg-gray-50 transition-colors"><a href="/orgs" class="text-primary hover:underline block">Local Meetup Groups</a></li>
                            <li class="p-4 hover:bg-gray-50 transition-colors"><a href="/orgs/build-carolina" class="text-primary hover:underline block">Build Carolina</a></li>
                            <li class="p-4 hover:bg-gray-50 transition-colors"><a href="/hg-nights" class="text-primary hover:underline block">HackGreenville Nights</a></li>
                            <li class="p-4 hover:bg-gray-50 transition-colors"><a href="https://refactorgvl.com" class="text-primary hover:underline block">RefactorGVL</a></li>
                            <li class="p-4 hover:bg-gray-50 transition-colors"><a href="/orgs/synergy-mill" class="text-primary hover:underline block">Synergy Mill</a></li>
                            <li class="p-4 hover:bg-gray-50 transition-colors"><a href="/orgs/upwise" class="text-primary hover:underline block">UpWiSE</a></li>
                        </ul>
                    </div>
                </div>
                <div>
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="bg-primary text-white p-4">
                            <h2 class="text-2xl font-bold text-center">Donate</h2>
                        </div>
                        <ul class="divide-y divide-gray-200">
                            <li class="p-4 hover:bg-gray-50 transition-colors"><a href="/orgs/agile-learning-institute" class="text-primary hover:underline block">Agile Learning Institute</a></li>
                            <li class="p-4 hover:bg-gray-50 transition-colors"><a href="/orgs/build-carolina" class="text-primary hover:underline block">Build Carolina</a></li>
                            <li class="p-4 hover:bg-gray-50 transition-colors"><a href="https://refactorgvl.com" class="text-primary hover:underline block">RefactorGVL</a></li>
                            <li class="p-4 hover:bg-gray-50 transition-colors"><a href="/orgs/synergy-mill" class="text-primary hover:underline block">Synergy Mill</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
@endsection
