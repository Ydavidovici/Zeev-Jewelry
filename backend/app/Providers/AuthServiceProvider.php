<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \Spatie\Permission\Models\Permission::class => \App\Policies\AdminPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        Log::channel('custom')->info('AuthServiceProvider booted. Policies registered.');

        // Define Gates based on user roles
        Gate::define('manage-users', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('manage-roles', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('manage-settings', function (User $user) {
            return $user->hasRole('admin');
        });

        // Gates for seller-specific actions
        Gate::define('manage-products', function (User $user) {
            return $user->hasRole('seller');
        });

        Gate::define('manage-orders', function (User $user) {
            return $user->hasRole('seller');
        });

        Gate::define('manage-inventory', function (User $user) {
            return $user->hasRole('seller');
        });

        Gate::define('manage-shipping', function (User $user) {
            return $user->hasRole('seller');
        });

        Gate::define('manage-payments', function (User $user) {
            return $user->hasRole('seller');
        });

        // Gates for customer-specific actions
        Gate::define('manage-cart', function (User $user) {
            return $user->hasRole('customer');
        });

        Gate::define('view-orders', function (User $user) {
            return $user->hasRole('customer');
        });

        Gate::define('manage-profile', function (User $user) {
            return $user->hasRole('customer');
        });

        Log::channel('custom')->info('Gates defined for roles');
    }
}
