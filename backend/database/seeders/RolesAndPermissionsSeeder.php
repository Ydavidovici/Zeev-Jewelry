<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions if they do not already exist
        $permissions = [
            // Categories
            'create-category', 'view-category', 'update-category', 'delete-category',

            // Customers
            'create-customer', 'view-customer', 'update-customer', 'delete-customer',

            // Inventories
            'create-inventory', 'view-inventory', 'update-inventory', 'delete-inventory',

            // Inventory Movements
            'create-inventory-movement', 'view-inventory-movement', 'update-inventory-movement', 'delete-inventory-movement',

            // Orders
            'create-order', 'view-order', 'update-order', 'delete-order',

            // Order Details
            'create-order-detail', 'view-order-detail', 'update-order-detail', 'delete-order-detail',

            // Payments
            'create-payment', 'view-payment', 'update-payment', 'delete-payment',

            // Products
            'create-product', 'view-product', 'update-product', 'delete-product',

            // Reviews
            'create-review', 'view-review', 'update-review', 'delete-review',

            // Roles
            'create-role', 'view-role', 'update-role', 'delete-role',

            // Shipping
            'create-shipping', 'view-shipping', 'update-shipping', 'delete-shipping',

            // Users
            'create-user', 'view-user', 'update-user', 'delete-user',

            // Permissions (add these)
            'create-permission', 'view-permission', 'update-permission', 'delete-permission',
        ];

        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
            }
        }

        // Create roles if they do not already exist
        $roles = [
            'admin', 'seller', 'customer'
        ];

        foreach ($roles as $role) {
            if (!Role::where('name', $role)->exists()) {
                Role::create(['name' => $role]);
            }
        }

        // Assign permissions to roles
        $admin = Role::findByName('admin');
        $admin->syncPermissions(Permission::all());

        // Assign relevant permissions to seller role
        $seller = Role::findByName('seller');
        $sellerPermissions = [
            'create-category', 'view-category', 'update-category', 'delete-category',
            'create-customer', 'view-customer', 'update-customer', 'delete-customer',
            'create-inventory', 'view-inventory', 'update-inventory', 'delete-inventory',
            'create-inventory-movement', 'view-inventory-movement', 'update-inventory-movement', 'delete-inventory-movement',
            'create-order', 'view-order', 'update-order', 'delete-order',
            'create-order-detail', 'view-order-detail', 'update-order-detail', 'delete-order-detail',
            'create-payment', 'view-payment', 'update-payment', 'delete-payment',
            'create-product', 'view-product', 'update-product', 'delete-product',
            'create-review', 'view-review', 'update-review', 'delete-review',
            'create-shipping', 'view-shipping', 'update-shipping', 'delete-shipping'
        ];
        $seller->syncPermissions($sellerPermissions);

        // Assign relevant permissions to customer role
        $customer = Role::findByName('customer');
        $customerPermissions = [
            'view-category', 'view-product', 'create-order', 'view-order', 'create-review', 'view-review'
        ];
        $customer->syncPermissions($customerPermissions);
    }
}
