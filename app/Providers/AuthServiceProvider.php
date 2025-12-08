<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
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
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gate for checking permissions
        Gate::define('has-permission', function (User $user, string $permission) {
            return $user->hasPermission($permission);
        });

        // Gate for checking roles
        Gate::define('has-role', function (User $user, string $role) {
            return $user->hasRole($role);
        });

        // Gate for admin access
        Gate::define('is-admin', function (User $user) {
            return $user->isAdmin();
        });
    }
}
