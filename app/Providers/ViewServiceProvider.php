<?php

namespace App\Providers;

use App\Models\Carousel;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        \View::composer('carousels.homepage', function ($view) {
            $carousel = Carousel::where('slug', 'homepage')->first();

            if(!$carousel){
                $view->with('carousel', false);
            }
            $view->with('carousel', $carousel);
        });
    }
}
