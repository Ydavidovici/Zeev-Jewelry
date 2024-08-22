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

        // Ensure the 'view-roles' and 'manage-roles' permissions exist
        $viewRolesPermission = Permission::firstOrCreate(['name' => 'view-roles', 'guard_name' => 'api']);
        $manageRolesPermission = Permission::firstOrCreate(['name' => 'manage-roles', 'guard_name' => 'api']);

        // Create the admin role and assign the necessary permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        $adminRole->syncPermissions([$viewRolesPermission, $manageRolesPermission]);
    }

    public function test_admin_can_view_roles()
    {
        // Create an admin user and assign the admin role
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin, 'api');

        // Attempt to view roles
        $response = $this->getJson('/admin/roles');

        // Assert that the response status is 200 OK
        $response->assertStatus(200)
            ->assertJsonStructure([
                'roles'
            ]);
    }

    public function test_admin_can_create_role()
    {
        // Create an admin user and assign the admin role
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin, 'api');

        // Attempt to create a new role
        $response = $this->postJson('/admin/roles', [
            'name' => 'new-role',
            'description' => 'This is a new role',
        ]);

        // Assert that the response status is 201 Created
        $response->assertStatus(201)
            ->assertJson([
                'role' => [
                    'name' => 'new-role'
                ]
            ]);

        // Assert that the role was created in the database
        $this->assertDatabaseHas('roles', ['name' => 'new-role']);
    }

    public function test_admin_can_update_role()
    {
        // Create an admin user and assign the admin role
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin, 'api');

        // Create a role to update
        $role = Role::create(['name' => 'updatable-role', 'guard_name' => 'api']);

        // Attempt to update the role
        $response = $this->putJson("/admin/roles/{$role->id}", [
            'name' => 'updated-role',
            'description' => 'Updated role description',  // Add description field
        ]);

        // Assert that the response status is 200 OK
        $response->assertStatus(200)
            ->assertJson([
                'role' => [
                    'name' => 'updated-role'
                ]
            ]);

        // Assert that the role was updated in the database
        $this->assertDatabaseHas('roles', ['name' => 'updated-role']);
    }


    public function test_admin_can_delete_role()
    {
        // Create an admin user and assign the admin role
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin, 'api');

        // Create a role to delete
        $role = Role::create(['name' => 'deletable-role', 'guard_name' => 'api']);

        // Attempt to delete the role
        $response = $this->deleteJson("/admin/roles/{$role->id}");

        // Assert that the response status is 204 No Content
        $response->assertStatus(204);

        // Assert that the role was deleted from the database
        $this->assertDatabaseMissing('roles', ['name' => 'deletable-role']);
    }
}
