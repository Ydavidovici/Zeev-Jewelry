<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\User;
use App\Policies\AdminPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\SellerPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => AdminPolicy::class,
        Customer::class => CustomerPolicy::class,
        User::class => SellerPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        // Define Gates
        Gate::define('access-admin-dashboard', [AdminPolicy::class, 'accessDashboard']);
        Gate::define('manage-users', [AdminPolicy::class, 'manageUsers']);
        Gate::define('manage-roles', [AdminPolicy::class, 'manageRoles']);
        Gate::define('manage-permissions', [AdminPolicy::class, 'managePermissions']);
        Gate::define('manage-settings', [AdminPolicy::class, 'manageSettings']);

        Gate::define('view-seller-dashboard', [SellerPolicy::class, 'viewDashboard']);
        Gate::define('manage-products', [SellerPolicy::class, 'manageProducts']);
        Gate::define('manage-orders', [SellerPolicy::class, 'manageOrders']);
        Gate::define('manage-inventory', [SellerPolicy::class, 'manageInventory']);
        Gate::define('manage-shipping', [SellerPolicy::class, 'manageShipping']);
        Gate::define('manage-payments', [SellerPolicy::class, 'managePayments']);

        Gate::define('view-any-customer', [CustomerPolicy::class, 'viewAny']);
        Gate::define('view-customer', [CustomerPolicy::class, 'view']);
        Gate::define('create-customer', [CustomerPolicy::class, 'create']);
        Gate::define('update-customer', [CustomerPolicy::class, 'update']);
        Gate::define('delete-customer', [CustomerPolicy::class, 'delete']);
    }
}

