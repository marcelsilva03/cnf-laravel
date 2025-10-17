<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UsersPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UsersPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates for roles
        Gate::define('access-dashboard', function ($user) {
            return $user->hasRole(['admin', 'socio-gestor', 'clienteapi']);
        });

        Gate::define('manage-users', function ($user) {
            return $user->hasRole(['admin', 'socio-gestor']);
        });

        Gate::define('manage-api', function ($user) {
            return $user->hasRole(['admin', 'clienteapi']);
        });
    }
}
