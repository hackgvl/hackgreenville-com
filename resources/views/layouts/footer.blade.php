<!-- Footer -->
<footer class="page-footer font-small bg-primary text-white pt-12 pb-6">

    <!-- Footer Links -->
    <div class="container-fluid text-center text-md-left max-w-7xl mx-auto">

        <!-- Grid row -->
        <div class="flex flex-wrap">

            <!-- Grid column for Social and Technology Icons -->
            <div class="w-full mt-0 mt-6 md:mt-0">
                <div class="flex justify-around flex-wrap flex-col md:flex-row gap-3">

                    <div class="flex flex-col items-center h-full order-1">
                        <h4 class="mb-4 text-xl font-semibold">Connect</h4>

                        <div class="text-center footer-social-icons">
                            <a href="https://www.meetup.com/hack-greenville/" class="footer-social-icon-wrapper inline-block mx-2" rel="nofollow" target="_blank">
                                <img src="{{url('img/icons/meetup.svg')}}" class="footer-social-icon"
                                     alt="Meetup" style="height: 65px;"/>
                            </a>
                            <a href="/join-slack" class="footer-social-icon-wrapper inline-block mx-2">
                                <img src="{{url('img/icons/slack.png')}}" class="footer-social-icon"
                                     alt="Slack"/>
                            </a>
                        </div>
                    </div>

                    <div class="flex flex-col items-center h-full order-2">
                        <h4 class="mb-4 text-xl font-semibold">Built with Laravel</h4>

                        <div class="text-center footer-technology-icons">
                            <a href="https://laravel.com" class="footer-technology-icon-wrapper" rel="nofollow">
                                <img src="{{url('img/icons/laravel-226015.png')}}" class="footer-technology-icon"
                                     alt="Laravel"/>
                            </a>
                        </div>
                    </div>

                    <!-- Grid column for Links -->
                    <div class="order-3 mt-8 md:mt-0">
                        <!-- Links -->
                        <h4 class="mb-4 text-xl font-semibold">Links</h4>

                        <ul class="list-unstyled grid grid-cols-2 gap-2 links-grid">
                            <li>
                                <a href="{{route('calendar.index')}}"
                                   class="text-gray-200 hover:text-white">
                                    <i class="fa fa-calendar"></i>
                                    {{__('Calendar')}}
                                </a>
                            </li>
                            <li>
                                <a href="{{route('events.index')}}"
                                   class="text-gray-200 hover:text-white">
                                    <i class="fa fa-calendar-check-o"></i>
                                    {{__('Events')}}
                                </a>
                            </li>
                            <li>
                                <a href="{{route('orgs.index')}}"
                                   class="text-gray-200 hover:text-white">
                                    <i class="fa fa-building"></i>
                                    {{__('Organizations')}}
                                </a>
                            </li>
                            <li>
                                <a href="{{route('labs.index')}}"
                                   class="text-gray-200 hover:text-white">
                                    <i class="fa fa-flask"></i>
                                    {{__('Labs')}}
                                </a>
                            </li>
                            <li>
                                <a href="{{route('hg-nights.index')}}"
                                   class="text-gray-200 hover:text-white">
                                    <i class="fa fa-moon-o"></i>
                                    {{__('HG Nights')}}
                                </a>
                            </li>
                            <li>
                                <a href="{{route('about')}}"
                                   class="text-gray-200 hover:text-white">
                                    <i class="fa fa-users"></i>
                                    {{__('About Us')}}
                                </a>
                            </li>
                            <li>
                                <a href="{{route('contribute')}}"
                                   class="text-gray-200 hover:text-white">
                                    <i class="fa fa-handshake-o"></i>
                                    {{__('Contribute')}}
                                </a>
                            </li>
                            <li>
                                <a href="{{route('contact')}}"
                                   class="text-gray-200 hover:text-white">
                                    <i class="fa fa-paper-plane"></i>
                                    {{__('Contact')}}
                                </a>
                            </li>
                            <li>
                                <a href="/join-slack"
                                   class="text-gray-200 hover:text-white">
                                    <i class="fa fa-slack"></i>
                                    {{__('Join Slack')}}
                                </a>
                            </li>
                        </ul>

                    </div>

                    <div class="order-4 mt-8 md:mt-0">
                        <!-- Links -->
                        <h4 class="mb-4 text-xl font-semibold">Other Links</h4>

                        <ul class="list-unstyled flex flex-col gap-2">
                            <li>
                                <a class="@if(Route::is('code-of-conduct.*')) font-semibold @endif text-gray-200 hover:text-white" href="{{route('code-of-conduct')}}">
                                    <i class="fa fa-check"></i>
                                    {{ __('Code of Conduct') }}
                                </a>
                            </li>
                            <li>
                                <a class="@if(Route::is('styles.*')) font-semibold @endif text-gray-200 hover:text-white" href="{{route('styles.index')}}">
                                    <i class="fa fa-picture-o"></i>
                                    {{ __('Our Style') }}
                                </a>
                            </li>
                            <li>
                                <a href="https://github.com/hackgvl/hackgreenville-com" target="_new"
                                   class="text-gray-200 hover:text-white">
                                    <i class="fa fa-github"></i>
                                    {{__('Join the Project')}}
                                </a>
                            </li>
                            <li>
                                <a href="/docs/api" target="_new"
                                   class="text-gray-200 hover:text-white">
                                    <i class="fa fa-wrench"></i>
                                    {{__('Explore Our API')}}
                                </a>
                            </li>
                        </ul>

                    </div>
                    <!-- End of Grid column -->
                </div>
            </div>
            <!-- End of Grid column -->

        </div>
        <!-- End of Grid row -->

    </div>
    <!-- End of Footer Links -->

    <!-- Copyright -->
    <div class="footer-copyright text-center py-6">
        <p class="text-sm">© {{date('Y')}} Copyright: <a href="http://hackgreenville.com" target="hg-home" class="text-success font-bold hover:text-green-400">HackGreenville</a>. HackGreenville is a program of the <a href="https://refactorgvl.com/" target="refactorgvl" class="text-success font-bold hover:text-green-400">RefactorGVL</a> non-profit.</p>
    </div>
    <!-- End of Copyright -->

</footer>
<!-- End of Footer -->