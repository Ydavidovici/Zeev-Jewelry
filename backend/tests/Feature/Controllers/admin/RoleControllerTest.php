<?php

namespace Tests\Feature\Controllers\Admin;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure the 'view-roles' or 'manage-roles' permission exists
        $viewRolesPermission = Permission::firstOrCreate(['name' => 'view-roles', 'guard_name' => 'web']);
        $manageRolesPermission = Permission::firstOrCreate(['name' => 'manage-roles', 'guard_name' => 'web']);

        // Create the admin role and assign the necessary permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions([$viewRolesPermission, $manageRolesPermission]);
    }

    public function test_admin_can_view_roles()
    {
        // Create an admin user and assign the admin role
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin);

        // Attempt to view roles
        $response = $this->getJson('/admin/roles');

        // Assert that the response status is 200 OK
        $response->assertStatus(200)
            ->assertJsonStructure([
                'roles'
            ]);
    }

    // Other tests for create, update, and delete roles
}
