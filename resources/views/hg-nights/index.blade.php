@extends('layouts.app', ['remove_space' => true])

@section('title', 'HackGreenville Nights')
@section('description', 'A quarterly event with social gathering and short talks for Greenville SC tech, hacker, tinkerer, maker, and DIY community members.')

@section('content')
  <div class="container">
    <div
      id="hg-nights-jumbotron"
      class="row text-white jumbotron jumbotron-fluid d-flex flex-column align-items-stretch"
    >
      <div class="col-sm-10 offset-md-2 col-md-4 py-5  justify-content-center text-center">
        <h1 class="display-1">{{ __('HackGreenville Nights') }}</h1>
        <div class="p-2 text-primary bg-light rounded col-sm-12">
          <p class="lead">
            A Quarterly Gathering of Greenville's Tech, Hacker, Tinkerer, Maker, and DIY Community
          </p>
          <a
            href="https://www.meetup.com/hack-greenville/"
            type="button"
            class="btn btn-success font-weight-bold"
            role="button"
            target="_blank">
              Join our Meetup Group
          </a>
        </div>
      </div>
    </div>

   <div class="col-10 px-5 offset-2 col-sm-8 offset-sm-4 col-xl-12 offset-xl-2 m-5 mx-auto d-flex flex-column">
      <h2 class="font-weight-bold">{{ __('Submit a Talk') }}</h2>
      <p>Talks are typically 5, 10, or 15 minutes on tech or tech-adjacent topics that don't fit the format of our existing local meetups or conferences.</p>
      <p>Thinking about starting a new group? Pitch the topic here and get a feel for the level of interest.</p>
         <a
            href="https://forms.gle/oz4vDwrwG9c4h5Bo6"
            rel="nofollow"
            type="button"
            class="btn btn-success font-weight-bold"
            role="button"
            target="_blank">
              Submit a Talk
          </a>
    </div>

    <div class="col-10 px-5 offset-2 col-sm-8 offset-sm-4 col-xl-12 offset-xl-2 m-5 mx-auto d-flex flex-column">
      <h2 class="font-weight-bold">{{ __('How to Get Involved') }}</h2>

      <ul class="list-group list-group-flush">
        <li class="list-group-item">Spread the word and invite others to <a href="https://forms.gle/oz4vDwrwG9c4h5Bo6" rel="nofollow">pitch a talk</a></li>
        <li class="list-group-item">Join our <a href="https://www.meetup.com/hack-greenville/"><em>Meetup</em> group</a> to receive updates</li>
        <li class="list-group-item">Hop into the <a href="/join-slack">HackGreenville Slack</a> <em>#community-organizers</em> channel to volunteer</li>
        <li class="list-group-item"><a href="/contact">Become a <em>HG Nights</em> sponsor</a></li>
      </ul>
    </div>

    <div class="col-10 px-5 offset-2 col-sm-8 offset-sm-4 col-xl-12 offset-xl-2 m-5 mx-auto d-flex flex-column">
      <h2 class="font-weight-bold">Past <em>HackGreenville Nights</em> Events</h2>


      <h3 class="mt-4">2025 - June | <em>"Full-stack Nachos"</em></h3>
      <div class="flex-row-3-columns w-100">
        <div class="flex-row-column">
          <p><a href="https://www.meetup.com/hack-greenville/events/307794466/" class="btn btn-info">Recap</a></p>
        </div>
        <div class="flex-row-column">
          <p><a href="https://www.youtube.com/@HackGreenville/playlists" class="btn btn-info">Videos</a></p>
        </div>
      </div>


      <h5 class="font-weight-bold">Kudos</h5>
      <p class="lead-text">Event Sponsorship by <a href="https://www.chirohd.com"><b>ChiroHD</b></a> | 
           Hosted by <a href="https://joinopenworks.com">OpenWorks Coworking</a> |
           Video by <a href="https://synergymill.com">Synergy Mill Makerspace</a>
      </p>

      <p>Food and event logistics by HackGreenville volunteers | Fiscal support by <a href="https://refactorgvl.com/">RefactorGVL</a></p>

      <h5 class="font-weight-bold">Speakers</h5>
      <ul class="list-group list-group-flush">
        <li class="list-group-item">Jen Bauer on
          <a href="https://www.youtube.com/watch?v=3KioPbXDfYo&list=PL8vFrjH8DfOGYQuir7fy0ccwDKw9SLCTD&index=1">
            <em>Level Up Your Legacy</em>
          </a>
	</li>
        <li class="list-group-item">Bogdan Kharchenko on
          <a href="https://www.youtube.com/watch?v=BDivM3znbzQ&list=PL8vFrjH8DfOGYQuir7fy0ccwDKw9SLCTD&index=2">
            <em>HackGreenville Labs</em>
          </a>
        </li>
        <li class="list-group-item">Violet Kester on
          <a href="https://www.youtube.com/watch?v=4PJSxQUz0oU&list=PL8vFrjH8DfOGYQuir7fy0ccwDKw9SLCTD&index=3">
            <em>Fluid Design and Musical Harmony: A simple method for implementing truly responsive web applications</em>
          </a>
        </li>
        <li class="list-group-item">AMA w/ Luke Kapustka, Nick Mansfield, JT Webb on
          <a href="https://www.youtube.com/watch?v=W5QzMMS10Q0&list=PL8vFrjH8DfOGYQuir7fy0ccwDKw9SLCTD&index=4">
            <em>We're Penetration Testers, Ask us Anything</em>
          </a>
        </li>
      </ul>



      <h3 class="mt-4">2025 - Feb | <em>"Souperman IV: Quest for Peas"</em></h3>
      <div class="flex-row-3-columns w-100">
        <div class="flex-row-column">
          <p><a href="https://www.meetup.com/hack-greenville/events/305856459/" class="btn btn-info">Recap</a></p>
        </div>
        <div class="flex-row-column">
          <p><a href="https://www.youtube.com/@HackGreenville/playlists" class="btn btn-info">Videos</a></p>
        </div>
      </div>


      <h5 class="font-weight-bold">Kudos</h5>
      <p class="lead-text">Event Sponsorship by <a href="https://home.mymechanic.app"><b>myMechanic</b></a> | 
           Hosted by <a href="https://joinopenworks.com">OpenWorks Coworking</a> |
           Video by <a href="https://synergymill.com">Synergy Mill Makerspace</a>
      </p>

      <p>Food and event logistics by HackGreenville volunteers | Fiscal support by <a href="https://refactorgvl.com/">RefactorGVL</a></p>

      <h5 class="font-weight-bold">Speakers</h5>
      <ul class="list-group list-group-flush">
        <li class="list-group-item">David He on
          <a href="https://www.youtube.com/watch?v=ZaRFJqOg28s&list=PL8vFrjH8DfOHEdVwM3ZlJGre_UBm3hWEa&index=1">
            <em>Beyond Coding: How Windsurf AI is Making Software Development Accessible to Everyone</em>
          </a>
	</li>
        <li class="list-group-item">Paul Sullivan on
          <a href="https://www.youtube.com/watch?v=u-2QHjU3Y3c&list=PL8vFrjH8DfOHEdVwM3ZlJGre_UBm3hWEa&index=2">
            <em>The Elixir Ecosystem</em>
          </a>
        </li>
        <li class="list-group-item">Zach Hall on
          <a href="https://www.youtube.com/watch?v=wfiDn5Ff2i4&list=PL8vFrjH8DfOHEdVwM3ZlJGre_UBm3hWEa&index=3">
            <em>Simulating Analog Television on the Web</em>
          </a>
        </li>
        <li class="list-group-item">Andrew Lechowicz on
          <a href="https://www.youtube.com/watch?v=OSVy1nGj5Y8&list=PL8vFrjH8DfOHEdVwM3ZlJGre_UBm3hWEa&index=4">
            <em>Detecting Flaky Tests: Increasing Trust in Your Test</em>
          </a>
        </li>
      </ul>

      <h3 class="mt-4">2024 - Oct | <em>"Starch Trek"</em></h3>
      <div class="flex-row-3-columns w-100">
        <div class="flex-row-column">
          <p><a href="https://www.meetup.com/hack-greenville/events/303551633/" class="btn btn-info">Recap</a></p>
        </div>
        <div class="flex-row-column">
          <p><a href="https://www.youtube.com/@HackGreenville/playlists" class="btn btn-info">Videos</a></p>
        </div>
      </div>


      <h5 class="font-weight-bold">Kudos</h5>
      <p class="lead-text">Event Sponsorship by <a href="https://www.brightball.com"><b>Brightball</b></a> | 
           Hosted by <a href="https://joinopenworks.com">OpenWorks Coworking</a> |
           Video by <a href="https://synergymill.com">Synergy Mill Makerspace</a>
      </p>

      <p>Food and event logistics by HackGreenville volunteers | Fiscal support by <a href="https://refactorgvl.com/">RefactorGVL</a></p>

      <h5 class="font-weight-bold">Speakers</h5>
      <ul class="list-group list-group-flush">
        <li class="list-group-item">Caleb McQuaid on
          <a href="https://www.youtube.com/watch?v=-1FoF2T2ZZU&list=PL8vFrjH8DfOHk8ACRhnu0WktPBVIwi-Dt&index=1">
            <em>Encore! Encore!</em>
          </a>
	</li>
        <li class="list-group-item"> Barry Jones on
          <a href="https://www.youtube.com/watch?v=AOis3O5kO70&list=PL8vFrjH8DfOHk8ACRhnu0WktPBVIwi-Dt&index=2">
            <em>Story Points are Pointless, Measure Queues</em>
          </a>
        </li>
        <li class="list-group-item"> Heather Bowes on
          <a href="https://www.youtube.com/watch?v=2Vsaq2bUl4E&list=PL8vFrjH8DfOHk8ACRhnu0WktPBVIwi-Dt&index=3">
            <em>Running a House Off an Electric Car</em>
          </a>
        </li>
        <li class="list-group-item">Brian Kennedy on
          <a href="https://www.youtube.com/watch?v=XW5J_KGWIAA&list=PL8vFrjH8DfOHk8ACRhnu0WktPBVIwi-Dt&index=4">
            <em>Project-based IaC, You're Doing IaC Wrong | Part 2 - Tools and Quality</em>
          </a>
        </li>
      </ul>

      <h3 class="mt-4">2024 - May | <em>"Subs and Sliders"</em></h3>
      <div class="flex-row-3-columns w-100">
        <div class="flex-row-column">
          <p><a href="https://www.meetup.com/hack-greenville/events/300300590/" class="btn btn-info">Recap</a></p>
        </div>
        <div class="flex-row-column">
          <p><a href="https://www.youtube.com/@HackGreenville/playlists" class="btn btn-info">Videos</a></p>
        </div>
      </div>


      <h5 class="font-weight-bold">Kudos</h5>
      <p class="lead-text">Event Sponsorship by <a href="https://www.neurelo.com"><b>Neurelo</b></a> | 
           Hosted by <a href="https://joinopenworks.com">OpenWorks Coworking</a> |  
           Video by <a href="https://synergymill.com">Synergy Mill Makerspace</a>
      </p>

      <p>Food and event logistics by HackGreenville volunteers | Fiscal support by <a href="https://refactorgvl.com/">RefactorGVL</a></p>

      <h5 class="font-weight-bold">Speakers</h5>
      <ul class="list-group list-group-flush">
        <li class="list-group-item">Andrew Thompson on
          <a href="https://www.youtube.com/watch?v=mWFX1lvIGSQ&list=PL8vFrjH8DfOHQ_cG3KC34jmlcNT55Ki_p&index=2&pp=iAQB">
            <em>How Not to Give a Talk</em>
          </a>
	</li>
        <li class="list-group-item"> James Shockley on
          <a href="https://www.youtube.com/watch?v=wMrcyxZO-Lw&list=PL8vFrjH8DfOHQ_cG3KC34jmlcNT55Ki_p&index=4&pp=iAQB">
            <em>Scratch, Dent, and Data: Engineering A Used Appliance Information Advantage Out Of Necessity</em>
          </a>
        </li>
        <li class="list-group-item"> Joel Griffith on
          <a href="https://www.youtube.com/watch?v=Yp79uQf923E&list=PL8vFrjH8DfOHQ_cG3KC34jmlcNT55Ki_p&index=3&pp=iAQB">
            <em>Random Numbers from Cosmic Rays (Step 1)</em>
          </a>
        </li>
        <li class="list-group-item">Pete Dunlap on
          <a href="https://www.youtube.com/watch?v=5rwe99c5dA0&list=PL8vFrjH8DfOHQ_cG3KC34jmlcNT55Ki_p&index=1&pp=iAQB">
            <em>Decentralized Community Building Via Twilio</em>
          </a>
        </li>
      </ul>



      <h3 class="mt-4">2024 - February | <em>"Chili Bar"</em></h3>
      <div class="flex-row-3-columns w-100">
        <div class="flex-row-column">
          <p><a href="https://www.meetup.com/hack-greenville/events/299063777/" class="btn btn-info">Recap</a></p>
        </div>
        <div class="flex-row-column">
          <p><a href="https://www.youtube.com/@HackGreenville/playlists" class="btn btn-info">Videos</a></p>
        </div>
      </div>


      <h5 class="font-weight-bold">Kudos</h5>
      <p class="lead-text">Event Sponsorship by <a href="https://stemsearchgroup.com/"><b>STEM Search Group</b></a> | 
           Hosted by <a href="https://joinopenworks.com">OpenWorks Coworking</a> |  
           Video by <a href="https://synergymill.com">Synergy Mill Makerspace</a>
      </p>

      <p>Food and event logistics by HackGreenville volunteers | Fiscal support by <a href="https://refactorgvl.com/">RefactorGVL</a></p>

      <h5 class="font-weight-bold">Speakers</h5>
      <ul class="list-group list-group-flush">
        <li class="list-group-item">Bogdan Kharchenko on
          <a href="https://www.youtube.com/watch?v=-BlfOb9Y7HY&list=PL8vFrjH8DfOHLH2hc_g87jLyiUWoupFtx&index=6&pp=iAQB">
            <em>Data Transfer Objects with PHP, as used by HackGreenville.com</em>
          </a>
	</li>

        <li class="list-group-item">Brian Kennedy on
          <a href="https://www.youtube.com/watch?v=9I_yclPk_T8&list=PL8vFrjH8DfOHLH2hc_g87jLyiUWoupFtx&index=2&pp=iAQB">
            <em>You're Doing IaC Wrong</em>
          </a>
	</li>

        <li class="list-group-item">Christina Roberts on
          <a href="https://www.youtube.com/watch?v=ZKOhdr6d9a4&list=PL8vFrjH8DfOHLH2hc_g87jLyiUWoupFtx&index=8&pp=iAQB">
            <em>Volunteer Opportunities w/ Agile Learning Institute</em>
          </a>
	</li>

        <li class="list-group-item">Gavin Coyle on
          <a href="https://www.youtube.com/watch?v=7njpu6PgNsw&list=PL8vFrjH8DfOHLH2hc_g87jLyiUWoupFtx&index=3&pp=iAQB">
            <em>Addressing Workforce Planning Challenges Through Mentorship</em>
          </a>
	</li>

        <li class="list-group-item">Jās Eckard on
          <a href="https://www.youtube.com/watch?v=945VSaNrJvM&list=PL8vFrjH8DfOHLH2hc_g87jLyiUWoupFtx&index=7&pp=iAQB">
            <em>Intro to UCLUG and BASH's History Feature</em>
          </a>
	</li>
        <li class="list-group-item">Jim Ciallella on
          <a href="https://www.youtube.com/watch?v=k5JhLpfGFMM&list=PL8vFrjH8DfOHLH2hc_g87jLyiUWoupFtx&index=5&pp=iAQB">
            <em>Lean Livin': How a Local Internet Provider Paid Me $6240 / hr</em>
          </a>
        </li>
        <li class="list-group-item">Pamela Wood Browne on
          <a href="https://www.youtube.com/watch?v=TvWIu_b3t2o&list=PL8vFrjH8DfOHLH2hc_g87jLyiUWoupFtx&index=4&pp=iAQB">
            <em>Intro to Code With The Carolinas and Current Projects</em>
          </a>
        </li>
        <li class="list-group-item">Paul on
          <a href="https://www.youtube.com/watch?v=ZVBo5VDso2Q&list=PL8vFrjH8DfOHLH2hc_g87jLyiUWoupFtx&index=1&pp=iAQB">
            <em>Censorship-resistant P2P Messaging via Briar and the Local-first Software Movement</em>
          </a>
        </li>
      </ul>

      <h3 class="mt-4">2023 - October | <em>"Taco Bar"</em></h3>

      <div class="flex-row-3-columns w-100">
        <div class="flex-row-column">
          <p><a href="https://www.meetup.com/hack-greenville/events/296051672/" class="btn btn-info">Recap</a></p>
        </div>
      </div>

      <h5 class="font-weight-bold">Kudos</h5>
      <p class="lead-text">Event Sponsorship by <a href="https://www.simplybinary.com"><b>Simply Binary</b></a> | 
           Hosted by <a href="https://joinopenworks.com">OpenWorks Coworking</a>
      </p>

      <p>Food and event logistics by HackGreenville volunteers</p>

      <h5 class="font-weight-bold">Speakers</h5>
      <ul class="list-group list-group-flush">
        <li class="list-group-item">Joey Loman on <em>Quick Intro to Synergy Mill Project Opportunities</em></li>
        <li class="list-group-item">Pete Broderick on <em>System Level Thinking</em></li>
        <li class="list-group-item">Olivia Sculley on <em>Quick Intro to HG Labs Project Opportunities</em></li>
        <li class="list-group-item">Robert Roskam on <em>How to Sanely Test Complex Systems</em></li>
        <li class="list-group-item">Ryan Lanciaux on <em>Cooking Up Better Applications with Inspiration from the Culinary World</em></li>
        <li class="list-group-item">Zach Robichaud on <em>Development Tooling with Laravel</em></li>
      </ul>

    </div>

  </div>
@endsection
