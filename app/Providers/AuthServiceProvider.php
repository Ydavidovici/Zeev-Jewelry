<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\{Category, Customer, Inventory, InventoryMovement, Order, OrderDetail, Payment, Product, Review, Role, Shipping, User};
use App\Policies\{CategoryPolicy, CustomerPolicy, InventoryPolicy, InventoryMovementPolicy, OrderPolicy, OrderDetailPolicy, PaymentPolicy, ProductPolicy, ReviewPolicy, RolePolicy, ShippingPolicy, UserPolicy};

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Category::class => CategoryPolicy::class,
        Customer::class => CustomerPolicy::class,
        Inventory::class => InventoryPolicy::class,
        InventoryMovement::class => InventoryMovementPolicy::class,
        Order::class => OrderPolicy::class,
        OrderDetail::class => OrderDetailPolicy::class,
        Payment::class => PaymentPolicy::class,
        Product::class => ProductPolicy::class,
        Review::class => ReviewPolicy::class,
        Role::class => RolePolicy::class,
        Shipping::class => ShippingPolicy::class,
        User::class => UserPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
