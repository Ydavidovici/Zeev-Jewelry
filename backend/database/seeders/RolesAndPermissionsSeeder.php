<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Clear cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Log::channel('custom')->info('Starting RolesAndPermissionsSeeder');

        // Define all possible permissions
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
            'create-user', 'view-user', 'update-user', 'delete-user',
            'create-permission', 'view-permission', 'update-permission', 'delete-permission',
            'manage-products', 'manage-orders', 'manage-inventory', 'manage-shipping',
            'manage-payments', 'manage-reports', 'upload-files',
            'manage-cart', 'view-orders', 'manage-profile', 'write-review',
            'view-checkout', 'manage-checkout', 'view-payments',
            'access-admin-dashboard'
        ];

        // Create all permissions with the guard 'api'
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'api']);
            Log::channel('custom')->info("Permission '{$permission}' created or already exists.");
        }

        // Define roles and their respective permissions
        $roles = [
            'admin' => $permissions, // Admin has all permissions
            'seller' => [
                'manage-products', 'manage-orders', 'manage-inventory', 'manage-shipping',
                'manage-payments', 'manage-reports', 'upload-files'
            ],
            'customer' => [
                'manage-cart', 'view-orders', 'manage-profile', 'write-review',
                'view-checkout', 'manage-checkout', 'view-payments'
            ],
        ];

        // Create roles and assign permissions
        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'api']);
            $role->syncPermissions($rolePermissions);
            Log::channel('custom')->info("Role '{$roleName}' created or already exists. Permissions assigned.");
        }

        Log::channel('custom')->info('RolesAndPermissionsSeeder completed.');
    }
}
