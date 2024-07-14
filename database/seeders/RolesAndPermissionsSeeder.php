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
            'create-category', 'view-category', 'update-category', 'delete-category',
            'create-customer', 'view-customer', 'update-customer', 'delete-customer',
            'create-inventory', 'view-inventory', 'update-inventory', 'delete-inventory',
            'create-inventory-movement', 'view-inventory-movement', 'update-inventory-movement', 'delete-inventory-movement',
            'create-order', 'view-order', 'update-order', 'delete-order',
            'create-order-detail', 'view-order-detail', 'update-order-detail', 'delete-order-detail',
            'create-payment', 'view-payment', 'update-payment', 'delete-payment',
            'create-product', 'view-product', 'update-product', 'delete-product',
            'create-review', 'view-review', 'update-review', 'delete-review',
            'create-role', 'view-role', 'update-role', 'delete-role',
            'create-shipping', 'view-shipping', 'update-shipping', 'delete-shipping',
            'create-user', 'view-user', 'update-user', 'delete-user'
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

        $customer = Role::findByName('customer');
        $customerPermissions = [
            'view-category', 'view-product', 'create-order', 'view-order', 'create-review', 'view-review'
        ];
        $customer->syncPermissions($customerPermissions);
    }
}
