<?php

namespace Tests;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SimplePermissionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_simple_policy_check()
    {
        // Ensure the 'admin' role exists
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $viewPermission = Permission::firstOrCreate(['name' => 'view-permission', 'guard_name' => 'web']);
        $adminRole->syncPermissions([$viewPermission]);

        // Create an admin user and assign the role
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        // Assertions to verify the role and permission are correctly assigned
        $this->assertTrue($admin->hasRole('admin'), 'User does not have the admin role.');
        $this->assertTrue($admin->hasPermissionTo('view-permission'), 'User does not have the view-permission.');

        // Log output for additional debugging before checking the policy
        Log::channel('custom')->info('Role and Permission Check', [
            'has_admin_role' => $admin->hasRole('admin'),
            'has_view_permission' => $admin->hasPermissionTo('view-permission'),
        ]);

        // Log output for debugging within the policy
        Log::channel('custom')->info('Running simple policy test for viewAny', [
            'user_id' => $admin->id,
            'roles' => $admin->getRoleNames(),
            'permissions' => $admin->getAllPermissions()->pluck('name')->toArray(),
        ]);

        // Assert that the user can execute the viewAny policy method
        $this->assertTrue($admin->can('viewAny', Permission::class), 'viewAny policy method failed.');
    }
}
