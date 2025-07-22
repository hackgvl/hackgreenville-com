<!-- Footer -->
<footer class="page-footer font-small bg-primary btn-secondary  pt-5 pb-3">

    <!-- Footer Links -->
    <div class="container-fluid text-center text-md-left">

        <!-- Grid row -->
        <div class="row">

            <!-- Grid column for Social and Technology Icons -->
            <div class="col-md-12 mt-md-0 mt-3">
                <div class="d-flex justify-content-around flex-wrap flex-column flex-md-row gap-3">

                    <div class="d-flex flex-column align-middle h-100 order-1 order-md-1">
                        <h4 class="mb-4">Built with Laravel</h4>

                        <div class="text-center footer-technology-icons">
                            <a href="https://laravel.com" class="footer-technology-icon-wrapper" rel="nofollow">
                                <img src="{{url('img/icons/laravel-226015.png')}}" class="footer-technology-icon"
                                     alt="Laravel"/>
                            </a>
                        </div>
                    </div>

                    <!-- Grid column for Links -->
                    <div class="order-2 order-md-2 mt-4 mt-md-0">
                        <!-- Links -->
                        <h4 class="mb-4">Links</h4>

                        <ul class="list-unstyled justify-content-around flex-wrap gap-2 links-grid">
                            <li>
                                <a
                                   href="{{route('calendar.index')}}">
                                    <i class="fa fa-calendar"></i>
                                    {{__('Calendar')}}
                                </a>
                            </li>
                            <li>
                                <a
                                   href="{{route('events.index')}}">
                                    <i class="fa fa-calendar-check-o"></i>
                                    {{__('Events')}}
                                </a>
                            </li>
                            <li>
                                <a
                                   href="{{route('orgs.index')}}">
                                    <i class="fa fa-building"></i>
                                    {{__('Organizations')}}
                                </a>
                            </li>
                            <li>
                                <a
                                   href="{{route('labs.index')}}">
                                    <i class="fa fa-flask"></i>
                                    {{__('Labs')}}
                                </a>
                            </li>
                            <li>
                                <a
                                   href="{{route('hg-nights.index')}}">
                                    <i class="fa fa-moon-o"></i>
                                    {{__('HG Nights')}}
                                </a>
                            </li>
                            <li>
                                <a
                                   href="{{route('about')}}">
                                    <i class="fa fa-users"></i>
                                    {{__('About Us')}}
                                </a>
                            </li>
                            <li>
                                <a
                                   href="{{route('contribute')}}">
                                    <i class="fa fa-handshake-o"></i>
                                    {{__('Contribute')}}
                                </a>
                            </li>
                            <li>
                                <a
                                   href="{{route('contact')}}">
                                    <i class="fa fa-paper-plane"></i>
                                    {{__('Contact')}}
                                </a>
                            </li>
                            <li>
                                <a href="/join-slack">
                                    <i class="fa fa-slack"></i>
                                    {{__('Join Slack')}}
                                </a>
                            </li>
                        </ul>

                    </div>

                    <div class="order-3 order-md-3 mt-4 mt-md-0">
                        <!-- Links -->
                        <h4 class="mb-4">Other Links</h4>

                        <ul class="list-unstyled d-flex justify-content-around flex-wrap gap-2 flex-column">
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
                            <li>
                                <a href="https://github.com/hackgvl/hackgreenville-com" target="_new">
                                    <i class="fa fa-github"></i>
                                    {{__('Join the Project')}}
                                </a>
                            </li>
                            <li>
                                <a href="/docs/api" target="_new">
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
    <div class="footer-copyright text-center py-3">
        <p>© {{date('Y')}} Copyright: <a href="http://hackgreenville.com" target="hg-home">HackGreenville</a>. HackGreenville is a program of the <a href="https://refactorgvl.com/" target="refactorgvl">RefactorGVL</a> non-profit.</p>
    </div>
    <!-- End of Copyright -->

</footer>
<!-- End of Footer -->