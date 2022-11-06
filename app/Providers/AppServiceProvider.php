<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        // $this->app->bind('path.public', function () {
        //     return base_path() . '/public_html';
        // });
        // $this->app->bind('path.public', function () {
        //     return base_path() . '/public_html';
        // });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // if ($this->app->environment('production')) {
        //     URL::forceScheme('https');
        // }
        if (config('app.env') != 'local') {
            URL::forceScheme('https');
        }
    }
}
