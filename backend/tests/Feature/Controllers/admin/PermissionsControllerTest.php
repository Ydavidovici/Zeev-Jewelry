<?php

namespace Tests\Feature\Controllers\Admin;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_logging_works()
    {
        Log::channel('custom')->info('Test log entry: Logging is working.');
        $this->assertTrue(true);
    }

    public function test_admin_can_view_permissions()
    {
        // Ensure permissions are created
        $viewPermission = Permission::firstOrCreate(['name' => 'view-permission']);
        $createPermission = Permission::firstOrCreate(['name' => 'create-permission']);
        $updatePermission = Permission::firstOrCreate(['name' => 'update-permission']);
        $deletePermission = Permission::firstOrCreate(['name' => 'delete-permission']);

        // Debugging: Output the permission names
        dump('Permissions created:', [
            $viewPermission->name,
            $createPermission->name,
            $updatePermission->name,
            $deletePermission->name,
        ]);

        // Create the admin role and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions([$viewPermission, $createPermission, $updatePermission, $deletePermission]);

        // Debugging: Check and output the role permissions
        dump('Admin role permissions:', $adminRole->permissions->pluck('name')->toArray());

        // Create an admin user and assign the admin role
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        // Assert that the user has the admin role
        $this->assertTrue($admin->hasRole('admin'), 'User does not have the admin role.');

        // Debugging: Output the user's assigned roles and permissions
        dump('Admin user roles:', $admin->roles->pluck('name')->toArray());
        dump('Admin user permissions:', $admin->getAllPermissions()->pluck('name')->toArray());

        // Check if the user has the specific permission to view any permissions
        $this->assertTrue($admin->can('viewAny', Permission::class), 'User does not have permission to view permissions.');

        // Debugging: Output whether the user can view any permissions
        dump('Can admin view permissions?', $admin->can('viewAny', Permission::class) ? 'Yes' : 'No');

        // Act as the admin user
        $this->actingAs($admin);

        // Make the request to the PermissionsController
        $response = $this->getJson('/admin/permissions');

        // Assert that the response status is 200 OK
        $response->assertStatus(200);

        // Assert the JSON structure of the response
        $response->assertJsonStructure([
            'permissions'
        ]);
    }
}
