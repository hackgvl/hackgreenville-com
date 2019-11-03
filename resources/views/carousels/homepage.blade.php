<div id="homepage" class="carousel slide" data-ride="carousel" style="{{$style ?: ''}}">
    <ol class="carousel-indicators">
        @foreach($carousel->slides as $index => $slide)
        <li data-target="#homepage" data-slide-to="{{$index}}" class=" @if($index == 0) active @endif"></li>
        @endforeach
    </ol>
    <div class="carousel-inner" role="listbox">
        @foreach($carousel->slides as $index => $slide)
        <div class="carousel-item @if($index == 0) active @endif">
            <div class="text-center">
                <img src="{{$slide}}" alt="Carousel slide">
            </div>
        </div>
        @endforeach
    </div>
    <a class="carousel-control-prev" href="#homepage" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#homepage" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
