<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(isset($_COOKIE['lang']))
            App::setLocale($_COOKIE['lang']);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
