<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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

    public function boot()
    {
      $this->app->bind(\Illuminate\Contracts\Routing\UrlGenerator::class, function ($app) {
            return new \Laravel\Lumen\Routing\UrlGenerator($app);
      });
        Schema::defaultStringLength(191);
    }
}
