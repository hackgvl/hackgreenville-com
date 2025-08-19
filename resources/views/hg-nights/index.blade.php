@extends('layouts.app', ['remove_space' => true])

@section('title', 'HackGreenville Nights')
@section('description', 'A quarterly event with social gathering and short talks for Greenville SC tech, hacker, tinkerer, maker, and DIY community members.')

@section('content')
  <div class="container-fluid px-0">
    <div
      id="hg-nights-jumbotron"
      class="relative bg-primary text-white py-20 flex items-center min-h-[500px]"
      style="background-image: url('{{ asset('img/hg-nights-sm.jpg') }}'); background-size: cover; background-position: center;"
    >
      <div class="absolute inset-0 bg-primary bg-opacity-75"></div>
      <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-2xl mx-auto text-center">
          <h1 class="text-5xl md:text-6xl font-bold mb-8">{{ __('HackGreenville Nights') }}</h1>
          <div class="bg-white text-primary rounded-lg p-6 mb-6">
            <p class="text-xl mb-6">
              A Quarterly Gathering of Greenville's Tech, Hacker, Tinkerer, Maker, and DIY Community
            </p>
            <a
              href="https://www.meetup.com/hack-greenville/"
              class="bg-success text-white px-6 py-3 rounded font-bold hover:bg-green-600 transition-colors inline-block"
              target="_blank">
                Join our Meetup Group
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="container mx-auto px-4 py-12">
      <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold mb-6">{{ __('Submit a Talk') }}</h2>
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
          <p class="text-lg mb-4">Talks are typically 5, 10, or 15 minutes on tech or tech-adjacent topics that don't fit the format of our existing local meetups or conferences.</p>
          <p class="text-lg mb-6">Thinking about starting a new group? Pitch the topic here and get a feel for the level of interest.</p>
          <a
            href="https://forms.gle/oz4vDwrwG9c4h5Bo6"
            rel="nofollow"
            class="bg-success text-white px-6 py-3 rounded font-bold hover:bg-green-600 transition-colors inline-block"
            target="_blank">
              Submit a Talk
          </a>
        </div>
      </div>
    </div>

    <div class="container mx-auto px-4 pb-12">
      <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold mb-6">{{ __('How to Get Involved') }}</h2>
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
          <ul class="divide-y divide-gray-200">
            <li class="p-4 hover:bg-gray-50 transition-colors">Spread the word and invite others to <a href="https://forms.gle/oz4vDwrwG9c4h5Bo6" rel="nofollow" class="text-primary hover:underline">pitch a talk</a></li>
            <li class="p-4 hover:bg-gray-50 transition-colors">Join our <a href="https://www.meetup.com/hack-greenville/" class="text-primary hover:underline"><em>Meetup</em> group</a> to receive updates</li>
            <li class="p-4 hover:bg-gray-50 transition-colors">Hop into the <a href="/join-slack" class="text-primary hover:underline">HackGreenville Slack</a> <em>#community-organizers</em> channel to volunteer</li>
            <li class="p-4 hover:bg-gray-50 transition-colors"><a href="/contact" class="text-primary hover:underline">Become a <em>HG Nights</em> sponsor</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="container mx-auto px-4 pb-12">
      <div class="max-w-6xl mx-auto">
        <h2 class="text-3xl font-bold mb-8">Past <em>HackGreenville Nights</em> Events</h2>


        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
          <h3 class="text-2xl font-bold mb-4">2025 - June | <em>"Full-stack Nachos"</em></h3>
          <div class="flex gap-4 mb-6">
            <a href="https://www.meetup.com/hack-greenville/events/307794466/" class="bg-info text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">Recap</a>
            <a href="https://www.youtube.com/@HackGreenville/playlists" class="bg-info text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">Videos</a>
          </div>
          <h5 class="text-lg font-bold mb-3">Kudos</h5>
          <p class="text-lg mb-4">Event Sponsorship by <a href="https://www.chirohd.com" class="text-primary hover:underline"><b>ChiroHD</b></a> | 
               Hosted by <a href="https://joinopenworks.com" class="text-primary hover:underline">OpenWorks Coworking</a> |
               Video by <a href="https://synergymill.com" class="text-primary hover:underline">Synergy Mill Makerspace</a>
          </p>
          <p class="mb-6">Food and event logistics by HackGreenville volunteers | Fiscal support by <a href="https://refactorgvl.com/" class="text-primary hover:underline">RefactorGVL</a></p>

          <h5 class="text-lg font-bold mb-3">Speakers</h5>
          <ul class="divide-y divide-gray-200 bg-gray-50 rounded">
            <li class="p-3">Jen Bauer on
              <a href="https://www.youtube.com/watch?v=3KioPbXDfYo&list=PL8vFrjH8DfOGYQuir7fy0ccwDKw9SLCTD&index=1" class="text-primary hover:underline">
                <em>Level Up Your Legacy</em>
              </a>
	    </li>
            <li class="p-3">Bogdan Kharchenko on
              <a href="https://www.youtube.com/watch?v=BDivM3znbzQ&list=PL8vFrjH8DfOGYQuir7fy0ccwDKw9SLCTD&index=2" class="text-primary hover:underline">
                <em>HackGreenville Labs</em>
              </a>
            </li>
            <li class="p-3">Violet Kester on
              <a href="https://www.youtube.com/watch?v=4PJSxQUz0oU&list=PL8vFrjH8DfOGYQuir7fy0ccwDKw9SLCTD&index=3" class="text-primary hover:underline">
                <em>Fluid Design and Musical Harmony: A simple method for implementing truly responsive web applications</em>
              </a>
            </li>
            <li class="p-3">AMA w/ Luke Kapustka, Nick Mansfield, JT Webb on
              <a href="https://www.youtube.com/watch?v=W5QzMMS10Q0&list=PL8vFrjH8DfOGYQuir7fy0ccwDKw9SLCTD&index=4" class="text-primary hover:underline">
                <em>We're Penetration Testers, Ask us Anything</em>
              </a>
            </li>
          </ul>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
          <h3 class="text-2xl font-bold mb-4">2025 - Feb | <em>"Souperman IV: Quest for Peas"</em></h3>
          <div class="flex gap-4 mb-6">
            <a href="https://www.meetup.com/hack-greenville/events/305856459/" class="bg-info text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">Recap</a>
            <a href="https://www.youtube.com/@HackGreenville/playlists" class="bg-info text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">Videos</a>
          </div>

          <h5 class="text-lg font-bold mb-3">Kudos</h5>
          <p class="text-lg mb-4">Event Sponsorship by <a href="https://home.mymechanic.app" class="text-primary hover:underline"><b>myMechanic</b></a> | 
               Hosted by <a href="https://joinopenworks.com" class="text-primary hover:underline">OpenWorks Coworking</a> |
               Video by <a href="https://synergymill.com" class="text-primary hover:underline">Synergy Mill Makerspace</a>
          </p>
          <p class="mb-6">Food and event logistics by HackGreenville volunteers | Fiscal support by <a href="https://refactorgvl.com/" class="text-primary hover:underline">RefactorGVL</a></p>

          <h5 class="text-lg font-bold mb-3">Speakers</h5>
          <ul class="divide-y divide-gray-200 bg-gray-50 rounded">
            <li class="p-3">David He on
              <a href="https://www.youtube.com/watch?v=ZaRFJqOg28s&list=PL8vFrjH8DfOHEdVwM3ZlJGre_UBm3hWEa&index=1" class="text-primary hover:underline">
                <em>Beyond Coding: How Windsurf AI is Making Software Development Accessible to Everyone</em>
              </a>
	    </li>
            <li class="p-3">Paul Sullivan on
              <a href="https://www.youtube.com/watch?v=u-2QHjU3Y3c&list=PL8vFrjH8DfOHEdVwM3ZlJGre_UBm3hWEa&index=2" class="text-primary hover:underline">
                <em>The Elixir Ecosystem</em>
              </a>
            </li>
            <li class="p-3">Zach Hall on
              <a href="https://www.youtube.com/watch?v=wfiDn5Ff2i4&list=PL8vFrjH8DfOHEdVwM3ZlJGre_UBm3hWEa&index=3" class="text-primary hover:underline">
                <em>Simulating Analog Television on the Web</em>
              </a>
            </li>
            <li class="p-3">Andrew Lechowicz on
              <a href="https://www.youtube.com/watch?v=OSVy1nGj5Y8&list=PL8vFrjH8DfOHEdVwM3ZlJGre_UBm3hWEa&index=4" class="text-primary hover:underline">
                <em>Detecting Flaky Tests: Increasing Trust in Your Test</em>
              </a>
            </li>
          </ul>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
          <h3 class="text-2xl font-bold mb-4">2024 - Oct | <em>"Starch Trek"</em></h3>
          <div class="flex gap-4 mb-6">
            <a href="https://www.meetup.com/hack-greenville/events/303551633/" class="bg-info text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">Recap</a>
            <a href="https://www.youtube.com/@HackGreenville/playlists" class="bg-info text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">Videos</a>
          </div>

          <h5 class="text-lg font-bold mb-3">Kudos</h5>
          <p class="text-lg mb-4">Event Sponsorship by <a href="https://www.brightball.com" class="text-primary hover:underline"><b>Brightball</b></a> | 
               Hosted by <a href="https://joinopenworks.com" class="text-primary hover:underline">OpenWorks Coworking</a> |
               Video by <a href="https://synergymill.com" class="text-primary hover:underline">Synergy Mill Makerspace</a>
          </p>
          <p class="mb-6">Food and event logistics by HackGreenville volunteers | Fiscal support by <a href="https://refactorgvl.com/" class="text-primary hover:underline">RefactorGVL</a></p>

          <h5 class="text-lg font-bold mb-3">Speakers</h5>
          <ul class="divide-y divide-gray-200 bg-gray-50 rounded">
            <li class="p-3">Caleb McQuaid on
              <a href="https://www.youtube.com/watch?v=-1FoF2T2ZZU&list=PL8vFrjH8DfOHk8ACRhnu0WktPBVIwi-Dt&index=1" class="text-primary hover:underline">
                <em>Encore! Encore!</em>
              </a>
	    </li>
            <li class="p-3"> Barry Jones on
              <a href="https://www.youtube.com/watch?v=AOis3O5kO70&list=PL8vFrjH8DfOHk8ACRhnu0WktPBVIwi-Dt&index=2" class="text-primary hover:underline">
                <em>Story Points are Pointless, Measure Queues</em>
              </a>
            </li>
            <li class="p-3"> Heather Bowes on
              <a href="https://www.youtube.com/watch?v=2Vsaq2bUl4E&list=PL8vFrjH8DfOHk8ACRhnu0WktPBVIwi-Dt&index=3" class="text-primary hover:underline">
                <em>Running a House Off an Electric Car</em>
              </a>
            </li>
            <li class="p-3">Brian Kennedy on
              <a href="https://www.youtube.com/watch?v=XW5J_KGWIAA&list=PL8vFrjH8DfOHk8ACRhnu0WktPBVIwi-Dt&index=4" class="text-primary hover:underline">
                <em>Project-based IaC, You're Doing IaC Wrong | Part 2 - Tools and Quality</em>
              </a>
            </li>
          </ul>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
          <h3 class="text-2xl font-bold mb-4">2024 - May | <em>"Subs and Sliders"</em></h3>
          <div class="flex gap-4 mb-6">
            <a href="https://www.meetup.com/hack-greenville/events/300300590/" class="bg-info text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">Recap</a>
            <a href="https://www.youtube.com/@HackGreenville/playlists" class="bg-info text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">Videos</a>
          </div>

          <h5 class="text-lg font-bold mb-3">Kudos</h5>
          <p class="text-lg mb-4">Event Sponsorship by <a href="https://www.neurelo.com" class="text-primary hover:underline"><b>Neurelo</b></a> | 
               Hosted by <a href="https://joinopenworks.com" class="text-primary hover:underline">OpenWorks Coworking</a> |  
               Video by <a href="https://synergymill.com" class="text-primary hover:underline">Synergy Mill Makerspace</a>
          </p>
          <p class="mb-6">Food and event logistics by HackGreenville volunteers | Fiscal support by <a href="https://refactorgvl.com/" class="text-primary hover:underline">RefactorGVL</a></p>

          <h5 class="text-lg font-bold mb-3">Speakers</h5>
          <ul class="divide-y divide-gray-200 bg-gray-50 rounded">
            <li class="p-3">Andrew Thompson on
              <a href="https://www.youtube.com/watch?v=mWFX1lvIGSQ&list=PL8vFrjH8DfOHQ_cG3KC34jmlcNT55Ki_p&index=2&pp=iAQB" class="text-primary hover:underline">
                <em>How Not to Give a Talk</em>
              </a>
	    </li>
            <li class="p-3"> James Shockley on
              <a href="https://www.youtube.com/watch?v=wMrcyxZO-Lw&list=PL8vFrjH8DfOHQ_cG3KC34jmlcNT55Ki_p&index=4&pp=iAQB" class="text-primary hover:underline">
                <em>Scratch, Dent, and Data: Engineering A Used Appliance Information Advantage Out Of Necessity</em>
              </a>
            </li>
            <li class="p-3"> Joel Griffith on
              <a href="https://www.youtube.com/watch?v=Yp79uQf923E&list=PL8vFrjH8DfOHQ_cG3KC34jmlcNT55Ki_p&index=3&pp=iAQB" class="text-primary hover:underline">
                <em>Random Numbers from Cosmic Rays (Step 1)</em>
              </a>
            </li>
            <li class="p-3">Pete Dunlap on
              <a href="https://www.youtube.com/watch?v=5rwe99c5dA0&list=PL8vFrjH8DfOHQ_cG3KC34jmlcNT55Ki_p&index=1&pp=iAQB" class="text-primary hover:underline">
                <em>Decentralized Community Building Via Twilio</em>
              </a>
            </li>
          </ul>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
          <h3 class="text-2xl font-bold mb-4">2024 - February | <em>"Chili Bar"</em></h3>
          <div class="flex gap-4 mb-6">
            <a href="https://www.meetup.com/hack-greenville/events/299063777/" class="bg-info text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">Recap</a>
            <a href="https://www.youtube.com/@HackGreenville/playlists" class="bg-info text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">Videos</a>
          </div>

          <h5 class="text-lg font-bold mb-3">Kudos</h5>
          <p class="text-lg mb-4">Event Sponsorship by <a href="https://stemsearchgroup.com/" class="text-primary hover:underline"><b>STEM Search Group</b></a> | 
               Hosted by <a href="https://joinopenworks.com" class="text-primary hover:underline">OpenWorks Coworking</a> |  
               Video by <a href="https://synergymill.com" class="text-primary hover:underline">Synergy Mill Makerspace</a>
          </p>
          <p class="mb-6">Food and event logistics by HackGreenville volunteers | Fiscal support by <a href="https://refactorgvl.com/" class="text-primary hover:underline">RefactorGVL</a></p>

          <h5 class="text-lg font-bold mb-3">Speakers</h5>
          <ul class="divide-y divide-gray-200 bg-gray-50 rounded">
            <li class="p-3">Bogdan Kharchenko on
              <a href="https://www.youtube.com/watch?v=-BlfOb9Y7HY&list=PL8vFrjH8DfOHLH2hc_g87jLyiUWoupFtx&index=6&pp=iAQB" class="text-primary hover:underline">
                <em>Data Transfer Objects with PHP, as used by HackGreenville.com</em>
              </a>
	    </li>
            <li class="p-3">Brian Kennedy on
              <a href="https://www.youtube.com/watch?v=9I_yclPk_T8&list=PL8vFrjH8DfOHLH2hc_g87jLyiUWoupFtx&index=2&pp=iAQB" class="text-primary hover:underline">
                <em>You're Doing IaC Wrong</em>
              </a>
	    </li>
            <li class="p-3">Christina Roberts on
              <a href="https://www.youtube.com/watch?v=ZKOhdr6d9a4&list=PL8vFrjH8DfOHLH2hc_g87jLyiUWoupFtx&index=8&pp=iAQB" class="text-primary hover:underline">
                <em>Volunteer Opportunities w/ Agile Learning Institute</em>
              </a>
	    </li>
            <li class="p-3">Gavin Coyle on
              <a href="https://www.youtube.com/watch?v=7njpu6PgNsw&list=PL8vFrjH8DfOHLH2hc_g87jLyiUWoupFtx&index=3&pp=iAQB" class="text-primary hover:underline">
                <em>Addressing Workforce Planning Challenges Through Mentorship</em>
              </a>
	    </li>
            <li class="p-3">Jās Eckard on
              <a href="https://www.youtube.com/watch?v=945VSaNrJvM&list=PL8vFrjH8DfOHLH2hc_g87jLyiUWoupFtx&index=7&pp=iAQB" class="text-primary hover:underline">
                <em>Intro to UCLUG and BASH's History Feature</em>
              </a>
	    </li>
            <li class="p-3">Jim Ciallella on
              <a href="https://www.youtube.com/watch?v=k5JhLpfGFMM&list=PL8vFrjH8DfOHLH2hc_g87jLyiUWoupFtx&index=5&pp=iAQB" class="text-primary hover:underline">
                <em>Lean Livin': How a Local Internet Provider Paid Me $6240 / hr</em>
              </a>
            </li>
            <li class="p-3">Pamela Wood Browne on
              <a href="https://www.youtube.com/watch?v=TvWIu_b3t2o&list=PL8vFrjH8DfOHLH2hc_g87jLyiUWoupFtx&index=4&pp=iAQB" class="text-primary hover:underline">
                <em>Intro to Code With The Carolinas and Current Projects</em>
              </a>
            </li>
            <li class="p-3">Paul on
              <a href="https://www.youtube.com/watch?v=ZVBo5VDso2Q&list=PL8vFrjH8DfOHLH2hc_g87jLyiUWoupFtx&index=1&pp=iAQB" class="text-primary hover:underline">
                <em>Censorship-resistant P2P Messaging via Briar and the Local-first Software Movement</em>
              </a>
            </li>
          </ul>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
          <h3 class="text-2xl font-bold mb-4">2023 - October | <em>"Taco Bar"</em></h3>
          <div class="flex gap-4 mb-6">
            <a href="https://www.meetup.com/hack-greenville/events/296051672/" class="bg-info text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">Recap</a>
          </div>

          <h5 class="text-lg font-bold mb-3">Kudos</h5>
          <p class="text-lg mb-4">Event Sponsorship by <a href="https://www.simplybinary.com" class="text-primary hover:underline"><b>Simply Binary</b></a> | 
               Hosted by <a href="https://joinopenworks.com" class="text-primary hover:underline">OpenWorks Coworking</a>
          </p>
          <p class="mb-6">Food and event logistics by HackGreenville volunteers</p>

          <h5 class="text-lg font-bold mb-3">Speakers</h5>
          <ul class="divide-y divide-gray-200 bg-gray-50 rounded">
            <li class="p-3">Joey Loman on <em>Quick Intro to Synergy Mill Project Opportunities</em></li>
            <li class="p-3">Pete Broderick on <em>System Level Thinking</em></li>
            <li class="p-3">Olivia Sculley on <em>Quick Intro to HG Labs Project Opportunities</em></li>
            <li class="p-3">Robert Roskam on <em>How to Sanely Test Complex Systems</em></li>
            <li class="p-3">Ryan Lanciaux on <em>Cooking Up Better Applications with Inspiration from the Culinary World</em></li>
            <li class="p-3">Zach Robichaud on <em>Development Tooling with Laravel</em></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
@endsection
