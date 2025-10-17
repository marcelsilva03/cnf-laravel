<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class JsonFileLoaderProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('JsonFileLoader', function ($app) {
            return new \App\Services\JsonFileLoader();
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
