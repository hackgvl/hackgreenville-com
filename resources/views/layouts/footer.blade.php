<!-- Footer -->
<footer class="page-footer font-small bg-primary btn-secondary  pt-4">

    <!-- Footer Links -->
    <div class="container-fluid text-center text-md-left">

        <!-- Grid row -->
        <div class="row">

            <!-- Grid column -->
            <div class="col-md-6 mt-md-0 mt-3">

                <!-- Content -->
                <h5 class="text-uppercase">Join US</h5>
                <p>Where we do fun new things in a friendly smart environment.</p>

            </div>
            <!-- Grid column -->

            <hr class="clearfix w-100 d-md-none pb-3">

            <!-- Grid column -->
            <div class="col-md-3 mb-md-0 mb-3">

            </div>
            <!-- Grid column -->

            <!-- Grid column -->
            <div class="col-md-3 mb-md-0 mb-3">

                <!-- Links -->
                <h5 class="text-uppercase">Links</h5>

                <ul class="list-unstyled">
                    <li>
                        <a class="@if(Route::is('calendar.*')) active @endif" href="{{route('calendar.index')}}">
                            <i class="fa fa-calendar"></i>
                            {{__('Calendar')}}
                        </a>
                    </li>
                    <li>
                        <a class="@if(Route::is('events.*')) active @endif" href="{{route('events.index')}}">
                            <i class="fa fa-calendar-check-o"></i>
                            {{__('Events')}}
                        </a>
                    </li>
                    <li>
                        <a href="#!">
                            <i class="fa fa-phone"></i>
                            {{__('Contact US')}}
                        </a>
                    </li>
                    <li>
                        <a href="#!">
                            <i class="fa fa-slack"></i>
                            {{__('Join Slack')}}
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
            <!-- Grid column -->

        </div>
        <!-- Grid row -->

    </div>
    <!-- Footer Links -->

    <!-- Copyright -->
    <div class="footer-copyright text-center py-3">© {{date('Y')}} Copyright:
        <a href="http://hackgreenville.com"> Hack Greenville</a>
    </div>
    <!-- Copyright -->

</footer>
<!-- Footer -->
