<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FakerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        if (class_exists(\Faker\Factory::class)) {
            $this->app->singleton(\Faker\Generator::class, function(){
                return \Faker\Factory::create('pt_BR');
            });
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
