<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        
    }

    function boot()
    {
        Schema::defaultStringLength(191);
    }

}
