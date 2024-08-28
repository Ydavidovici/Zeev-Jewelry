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
            'view-checkout', 'manage-checkout', 'view-payments'
        ];

        // Create all permissions with the guard 'api'
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'api']);
            Log::channel('custom')->info("Permission '{$permission}' created or exists already.");
        }

        // Define roles
        $roles = ['admin', 'seller', 'customer'];

        // Create roles with the guard 'api'
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'api']);
            Log::channel('custom')->info("Role '{$role}' created or exists already.");
        }

        // Assign all permissions to admin role
        $adminRole = Role::where('name', 'admin')->where('guard_name', 'api')->first();
        $adminRole->syncPermissions(Permission::where('guard_name', 'api')->get());
        Log::channel('custom')->info("All permissions assigned to 'admin' role.");

        // Define specific permissions for seller and assign them
        $sellerPermissions = ['manage-products', 'manage-orders', 'manage-inventory', 'manage-shipping', 'manage-payments', 'manage-reports', 'upload-files'];
        $sellerRole = Role::where('name', 'seller')->where('guard_name', 'api')->first();
        $sellerRole->syncPermissions(Permission::whereIn('name', $sellerPermissions)->where('guard_name', 'api')->get());
        Log::channel('custom')->info("Permissions assigned to 'seller' role.");

        // Define specific permissions for customer and assign them
        $customerPermissions = ['manage-cart', 'view-orders', 'manage-profile', 'write-review', 'view-checkout', 'manage-checkout', 'view-payments'];
        $customerRole = Role::where('name', 'customer')->where('guard_name', 'api')->first();
        $customerRole->syncPermissions(Permission::whereIn('name', $customerPermissions)->where('guard_name', 'api')->get());
        Log::channel('custom')->info("Permissions assigned to 'customer' role.");

        Log::channel('custom')->info('RolesAndPermissionsSeeder completed.');
    }
}
