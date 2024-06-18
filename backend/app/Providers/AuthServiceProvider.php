<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('create-category', function ($user){
            return $user->hasRole('admin') || $user->hasRole('seller');
        });

        Gate::define('create-customer', function ($user){
            return $user->hasRole('admin') || $user->hasRole('seller');
        });

        Gate::define('create-inventory', function ($user){
            return $user->hasRole('admin') || $user->hasRole('seller');
        });

        Gate::define('create-inventory-movement', function ($user){
            return $user->hasRole('admin') || $user->hasRole('seller');
        });

        Gate::define('create-order', function ($user){
            return $user->hasRole('admin') || $user->hasRole('seller') || $user->hasRole('customer');
        });

        Gate::define('create-order-detail', function ($user){
            return $user->hasRole('admin') || $user->hasRole('seller') || $user->hasRole('customer');
        });

        Gate::define('create-payment', function ($user){
            return $user->hasRole('admin') || $user->hasRole('seller') || $user->hasRole('customer');
        });

        Gate::define('create-product', function ($user){
            return $user->hasRole('admin') || $user->hasRole('seller');
        });

        Gate::define('create-review', function ($user){
            return $user->hasRole('admin') || $user->hasRole('customer');
        });

        Gate::define('create-role', function ($user){
            return $user->hasRole('admin');
        });

        Gate::define('create-shipping', function ($user){
            return $user->hasRole('admin') || $user->hasRole('seller');
        });

        Gate::define('create-user', function ($user){
            return $user->hasRole('admin');
        });

        Gate::define('view-category', function ($user){
            return $user->hasRole('admin') || $user->hasRole('seller') || $user->hasRole('customer');
        });

        Gate::define('view-customer', function ($user){
            return $user->hasRole('admin') || $user->hasRole('seller');
        });

        Gate::define('view-inventory', function ($user){
            return $user->hasRole('admin') || $user->hasRole('seller');
        });

        Gate::define('view-movement', function ($user){
            return $user->hasRole('admin') || $user->hasRole('seller');
        });

        Gate::define('view-order', function ($user){
            return $user->hasRole('admin') || $user->hasRole('seller') || $user->hasRole('customer');
        });

        Gate::define('view-order-detail', function ($user){
            return $user->hasRole('admin') || $user->hasRole('seller') || $user->hasRole('customer');
        });

        Gate::define('view-payment', function ($user){
            return $user->hasRole('admin') || $user->hasRole('seller') || $user->hasRole('customer');
        });

        Gate::define('view-product', function ($user){
            return $user->hasRole('admin') || $user->hasRole('seller') || $user->hasRole('customer');
        });

        Gate::define('view-review', function ($user){
            return $user->hasRole('admin') || $user->hasRole('seller') || $user->hasRole('customer');
        });

        Gate::define('view-role', function ($user){
            return $user->hasRole('admin');
        });

        Gate::define('view-shipping', function ($user){
            return $user->hasRole('admin') || $user->hasRole('seller');
        });

        Gate::define('view-user', function ($user){
            return $user->hasRole('admin');
        });
    }
}
