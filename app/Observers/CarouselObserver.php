<?php

namespace App\Observers;

use App\Models\Carousel;

class CarouselObserver
{
    /**
     * Handle the carousel "created" event.
     *
     * @param Carousel $carousel
     * @return void
     */
    public function created(Carousel $carousel)
    {
        //
    }

    /**
     * Handle the carousel "updated" event.
     *
     * @param Carousel $carousel
     * @return void
     */
    public function updated(Carousel $carousel)
    {
        //
    }

    /**
     * Handle the carousel "deleted" event.
     *
     * @param Carousel $carousel
     * @return void
     */
    public function deleted(Carousel $carousel)
    {
        // TODO :: delete the image from disk
    }

    /**
     * Handle the carousel "restored" event.
     *
     * @param Carousel $carousel
     * @return void
     */
    public function restored(Carousel $carousel)
    {
        //
    }

    /**
     * Handle the carousel "force deleted" event.
     *
     * @param Carousel $carousel
     * @return void
     */
    public function forceDeleted(Carousel $carousel)
    {
        // TODO :: delete the image from disk
    }
}
