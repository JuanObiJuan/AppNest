<?php

namespace App\Providers;

use App\Services\GoogleTTSService;
use Illuminate\Support\ServiceProvider;

class GoogleTTSServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('GoogleTTSService', function ($app) {
            return new GoogleTTSService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
