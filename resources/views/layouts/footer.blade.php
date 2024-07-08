<!-- Footer -->
<footer class="page-footer font-small bg-primary btn-secondary  pt-5 pb-3">

    <!-- Footer Links -->
    <div class="container-fluid text-center text-md-left">

        <!-- Grid row -->
        <div class="row">

            <!-- Grid column for Social and Technology Icons -->
            <div class="col-md-12 mt-md-0 mt-3">
                <div class="d-flex justify-content-around flex-wrap gap-3">
                    <div>
                        <!-- Content -->
                        {{--                        <h4 class="mb-4">Join US</h4>--}}

                        <div class="text-center footer-social-icons">
                            <a href="/events" class="footer-social-icon-wrapper">
                                <img src="{{url('img/icons/meetup.svg')}}" class="footer-social-icon" alt="Meetup"/>
                            </a>
                            <a href="/join-slack" class="footer-social-icon-wrapper ml-5">
                                <img src="{{url('img/icons/slack.png')}}" class="footer-social-icon" alt="Slack"/>
                            </a>
                        </div>
                    </div>

                    <div class="d-flex flex-column align-middle h-100">
                        <h4 class="mb-4">Built with Laravel</h4>

                        <div class="text-center footer-technology-icons">
                            <a href="https://laravel.com" class="footer-technology-icon-wrapper" rel="nofollow">
                                <img src="{{url('img/icons/laravel-226015.png')}}" class="footer-technology-icon"
                                     alt="Laravel"/>
                            </a>
                        </div>
                    </div>

                    <!-- Grid column for Links -->
                    <div>
                        <!-- Links -->
                        <h4 class="mb-4">Links</h4>

                        <ul class="list-unstyled d-flex justify-content-around flex-wrap gap-2 flex-column">
                            <li>
                                <a class="@if(Route::is('calendar.*')) active @endif"
                                   href="{{route('calendar.index')}}">
                                    <i class="fa fa-calendar"></i>
                                    {{__('Calendar')}}
                                </a>
                            </li>
                            <li>
                                <a class="@if(Route::is('events.*')) active @endif"
                                   href="{{route('events.index')}}">
                                    <i class="fa fa-calendar-check-o"></i>
                                    {{__('Events')}}
                                </a>
                            </li>
                            <li>
                                <a class="@if(Route::is('join-slack.*')) active @endif" href="/join-slack">
                                    <i class="fa fa-slack"></i>
                                    {{__('Join Slack')}}
                                </a>
                            </li>
                            <li>
                                <a class="@if(Route::is('code-of-conduct.*')) active @endif" href="{{route('code-of-conduct')}}">
                                    <i class="fa fa-check"></i>
                                    {{ __('Code of Conduct') }}
                                </a>
                            </li>
                            <li>
                                <a class="@if(Route::is('styles.*')) active @endif" href="{{route('styles.index')}}">
                                    <i class="fa fa-picture-o"></i>
                                    {{ __('Our Style') }}
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
    <div class="footer-copyright text-center py-3">
        <p>© {{date('Y')}} Copyright: <a href="http://hackgreenville.com" target="hg-home">HackGreenville</a>. HackGreenville is a program of the <a href="https://refactorgvl.com/" target="refactorgvl">RefactorGVL</a> non-profit.</p>
    </div>
    <!-- End of Copyright -->

</footer>
<!-- End of Footer -->
