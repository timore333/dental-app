<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Visit;
use App\Policies\AppointmentPolicy;
use App\Policies\UserPolicy;
use App\Policies\VisitPolicy;
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
        Appointment::class => AppointmentPolicy::class,
        Visit::class => VisitPolicy::class,
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
