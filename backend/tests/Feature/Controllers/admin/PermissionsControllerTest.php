<?php

namespace Tests\Feature\Controllers\Admin;

use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create 'admin' role if it doesn't exist
        if (!Role::where('name', 'admin')->exists()) {
            $adminRole = Role::create(['name' => 'admin']);
        } else {
            $adminRole = Role::where('name', 'admin')->first();
        }

        // Create 'manage permissions' permission if it doesn't exist
        if (!Permission::where('name', 'manage permissions')->exists()) {
            $managePermissions = Permission::create(['name' => 'manage permissions']);
            // Assign 'manage permissions' to 'admin' role
            $adminRole->givePermissionTo($managePermissions);
        }
    }

    public function test_admin_can_view_permissions()
    {
        // Create an admin user and assign the 'admin' role
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // Act as the admin user and try to access the permissions index route
        $response = $this->actingAs($admin, 'api')
            ->getJson(route('admin.permissions.index'));

        // Assert that the response status is 200 OK and has the correct structure
        $response->assertStatus(200)
            ->assertJsonStructure([
                'permissions' => [
                    '*' => ['id', 'name', 'guard_name', 'created_at', 'updated_at']
                ]
            ]);
    }

    public function test_non_admin_cannot_view_permissions()
    {
        // Create a non-admin user
        $user = User::factory()->create();

        // Act as the non-admin user and try to access the permissions index route
        $response = $this->actingAs($user, 'api')
            ->getJson(route('admin.permissions.index'));

        // Assert that the response status is 403 Forbidden
        $response->assertStatus(403);
    }

    public function test_admin_can_create_permission()
    {
        // Create an admin user and assign the 'admin' role
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // Act as the admin user and try to create a new permission
        $response = $this->actingAs($admin, 'api')
            ->postJson(route('admin.permissions.store'), ['name' => 'edit products']);

        // Assert that the response status is 201 Created and contains the new permission data
        $response->assertStatus(201)
            ->assertJson([
                'permission' => [
                    'name' => 'edit products'
                ]
            ]);
    }

    public function test_non_admin_cannot_create_permission()
    {
        // Create a non-admin user
        $user = User::factory()->create();

        // Act as the non-admin user and try to create a permission
        $response = $this->actingAs($user, 'api')
            ->postJson(route('admin.permissions.store'), ['name' => 'edit products']);

        // Assert that the response status is 403 Forbidden
        $response->assertStatus(403);
    }
}
