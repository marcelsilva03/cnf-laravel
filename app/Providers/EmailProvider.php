<?php

namespace App\Providers;

use App\Services\MailerService;
use Illuminate\Support\ServiceProvider;
class EmailProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(MailerService::class, function ($app) {
            return new MailerService();
        });
    }
}
