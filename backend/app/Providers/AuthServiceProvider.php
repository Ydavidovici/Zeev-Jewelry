<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\User;
use App\Policies\AdminPolicy;
use App\Policies\CustomerPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Customer::class => CustomerPolicy::class,
        User::class => AdminPolicy::class,
        \Spatie\Permission\Models\Permission::class => \App\Policies\AdminPolicy::class,
        \Spatie\Permission\Models\Role::class => \App\Policies\RolePolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        // Define Gates for Admin actions, considering using policies
        Gate::define('manage-users', [AdminPolicy::class, 'manageUsers']);
        Gate::define('manage-roles', [AdminPolicy::class, 'manageRoles']);
        Gate::define('manage-settings', [AdminPolicy::class, 'manageSettings']);

        // Define Gates for Seller actions
        Gate::define('view-seller-dashboard', function (User $user) {
            return $user->hasRole('seller');
        });

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

        // Define Gates for Customer actions, delegating to CustomerPolicy
        Gate::define('view-any-customer', [CustomerPolicy::class, 'viewAny']);
        Gate::define('view-customer', [CustomerPolicy::class, 'view']);
        Gate::define('create-customer', [CustomerPolicy::class, 'create']);
        Gate::define('update-customer', [CustomerPolicy::class, 'update']);
        Gate::define('delete-customer', [CustomerPolicy::class, 'delete']);
    }
}
