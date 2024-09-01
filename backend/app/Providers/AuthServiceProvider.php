<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Admin-specific gates
        Gate::define('access-admin-dashboard', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('manage-users', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('manage-roles', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('manage-permissions', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('manage-settings', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('handle-webhooks', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('manage-reports', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('manage-shipping', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('manage-products', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('manage-inventory', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('manage-payments', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('view-sensitive-data', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('upload-files', function (User $user) {
            return $user->hasRole('admin');
        });

        Gate::define('delete-files', function (User $user) {
            return $user->hasRole('admin');
        });

        // Seller-specific gates
        Gate::define('access-seller-dashboard', function (User $user) {
            return $user->hasRole('seller');
        });

        Gate::define('manage-products-seller', function (User $user) {
            return $user->hasRole('seller');
        });

        Gate::define('manage-orders-seller', function (User $user) {
            return $user->hasRole('seller');
        });

        Gate::define('manage-inventory-seller', function (User $user) {
            return $user->hasRole('seller');
        });

        Gate::define('manage-shipping-seller', function (User $user) {
            return $user->hasRole('seller');
        });

        Gate::define('manage-payments-seller', function (User $user) {
            return $user->hasRole('seller');
        });

        Gate::define('manage-reports-seller', function (User $user) {
            return $user->hasRole('seller');
        });

        Gate::define('upload-files-seller', function (User $user) {
            return $user->hasRole('seller');
        });

        // Customer-specific gates
        Gate::define('manage-cart', function (User $user) {
            return $user->hasRole('customer');
        });

        Gate::define('view-orders', function (User $user) {
            return $user->hasRole('customer');
        });

        Gate::define('manage-profile', function (User $user) {
            return $user->hasRole('customer');
        });

        Gate::define('write-review', function (User $user) {
            return $user->hasRole('customer');
        });

        Gate::define('view-checkout', function (User $user) {
            return $user->hasRole('customer');
        });

        Gate::define('manage-checkout', function (User $user) {
            return $user->hasRole('customer');
        });

        Gate::define('view-payments', function (User $user) {
            return $user->hasRole('customer');
        });
    }
}
