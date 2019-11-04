@extends('layouts.app', ['remove_space' => true])

@section('title', 'Hackgreenville - A Developer Community in the Greenville SC Area')

@section('content')

    <div id="carouselIndicators" class="homepage carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselIndicators" data-slide-to="1"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100" src="/img/hackgreenville-banner.jpg" alt="Greenville works together"/>
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="/img/meetup.jpeg" alt="Greenville works together"/>
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="py-4">
                    <div class="card">
                        <div class="card-header">
                            Quote
                        </div>
                        <div class="card-body">
                            <blockquote class="blockquote mb-0">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                                <footer class="blockquote-footer">Someone famous in <cite title="Source Title">Source Title</cite></footer>
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="plate">col-6 (L 50%)<img class="card-img" src="http://placehold.it/400x500" alt=""></div>
                <br>
                <div class="row">
                    <div class="col-6">
                        <div class="plate">col-6 (L 50%)<img class="card-img" src="http://placehold.it/400x400" alt=""></div>
                    </div>
                    <div class="col-6">
                        <div class="plate">col-6 (L 50%)<img class="card-img" src="http://placehold.it/400x400" alt=""></div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="plate">col-6 (R 50%)<img class="card-img" src="http://placehold.it/600x400" alt=""></div>
                <br>
                <div class="row">
                    <div class="col-7">
                        <div class="plate">R 60%<img class="card-img" src="http://placehold.it/400x400" alt=""></div>
                    </div>
                    <div class="col-5">
                        <div class="plate">R 40%<img class="card-img" src="http://placehold.it/400x605" alt=""></div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-5">
                        <div class="plate">R 40%<img class="card-img" src="http://placehold.it/400x605" alt=""></div>
                    </div>
                    <div class="col-7">
                        <div class="plate">R 60%<img class="card-img" src="http://placehold.it/400x400" alt=""></div>
                    </div>
                </div>

            </div>

        </div>

        <hr>

        <div class="row">
            <div class="col-md-6">
                List of comments in timeline format, maybe.
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Past Events and what we learned
                    </div>
                    <div class="card-body">
                        <blockquote class="blockquote mb-0">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                            <footer class="blockquote-footer">Someone famous in <cite title="Source Title">Source Title</cite></footer>
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

