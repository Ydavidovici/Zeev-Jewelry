<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\User;
use App\Policies\AdminPolicy;
use App\Policies\CustomerPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

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

        Log::channel('custom')->info('AuthServiceProvider booted. Policies registered.');

        // Define Gates for Admin actions
        Gate::define('manage-users', [AdminPolicy::class, 'manageUsers']);
        Gate::define('manage-roles', [AdminPolicy::class, 'manageRoles']);
        Gate::define('manage-settings', [AdminPolicy::class, 'manageSettings']);
        Log::channel('custom')->info('Admin gates defined');

        // Define Gates for Seller actions
        Gate::define('view-seller-dashboard', function (User $user) {
            Log::channel('custom')->info('Checking view-seller-dashboard gate', ['user' => $user->email]);
            return $user->hasRole('seller');
        });

        Gate::define('manage-products', function (User $user) {
            Log::channel('custom')->info('Checking manage-products gate', ['user' => $user->email]);
            return $user->hasRole('seller');
        });

        Gate::define('manage-orders', function (User $user) {
            Log::channel('custom')->info('Checking manage-orders gate', ['user' => $user->email]);
            return $user->hasRole('seller');
        });

        Gate::define('manage-inventory', function (User $user) {
            Log::channel('custom')->info('Checking manage-inventory gate', ['user' => $user->email]);
            return $user->hasRole('seller');
        });

        Gate::define('manage-shipping', function (User $user) {
            Log::channel('custom')->info('Checking manage-shipping gate', ['user' => $user->email]);
            return $user->hasRole('seller');
        });

        Gate::define('manage-payments', function (User $user) {
            Log::channel('custom')->info('Checking manage-payments gate', ['user' => $user->email]);
            return $user->hasRole('seller');
        });

        Log::channel('custom')->info('Seller gates defined');

        // Define Gates for Customer actions
        Gate::define('view-any-customer', [CustomerPolicy::class, 'viewAny']);
        Gate::define('view-customer', [CustomerPolicy::class, 'view']);
        Gate::define('create-customer', [CustomerPolicy::class, 'create']);
        Gate::define('update-customer', [CustomerPolicy::class, 'update']);
        Gate::define('delete-customer', [CustomerPolicy::class, 'delete']);
        Log::channel('custom')->info('Customer gates defined');
    }
}
