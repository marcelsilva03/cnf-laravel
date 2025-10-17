<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Filament services
        $this->app->register(\Filament\FilamentServiceProvider::class);
        $this->app->register(\Filament\Support\SupportServiceProvider::class);
        $this->app->register(\Filament\Forms\FormsServiceProvider::class);
        $this->app->register(\Filament\Tables\TablesServiceProvider::class);
        $this->app->register(\Filament\Notifications\NotificationsServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            \DB::connection()->getPdo();
        } catch (\Exception $e) {
            \Log::error('Database connection failed');
        }

        // Register view composers
        View::composer('*', function ($view) {
            $view->with('auth', auth());
        });
    }
}
