<?php

namespace Tests;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimpleRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_roles()
    {
        // Ensure the necessary permissions exist
        $viewRolesPermission = Permission::firstOrCreate(['name' => 'view-roles', 'guard_name' => 'web']);
        $manageRolesPermission = Permission::firstOrCreate(['name' => 'manage-roles', 'guard_name' => 'web']);

        // Create the admin role and assign the necessary permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions([$viewRolesPermission, $manageRolesPermission]);

        // Create an admin user and assign the admin role
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        // Act as the admin user
        $this->actingAs($admin);

        // Verify that the admin can view roles
        $response = $this->getJson('/api/admin/roles');
        $response->assertStatus(200);

        // Ensure the 'editor' role does not exist for 'web' guard before creating
        Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);

        // Verify that the admin can create a role
        $response = $this->postJson('/api/admin/roles', [
            'name' => 'editor',
            'description' => 'Editor Role',
            'guard_name' => 'web',
        ]);
        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'editor'
            ]);

        // Verify that the admin can update a role
        $role = Role::where('name', 'editor')->where('guard_name', 'web')->first();
        $response = $this->putJson("/api/admin/roles/{$role->id}", [
            'name' => 'super editor',
            'description' => 'Super Editor Role',
            'guard_name' => 'web',
        ]);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'super editor'
            ]);

        // Verify that the admin can delete a role
        $response = $this->deleteJson("/api/admin/roles/{$role->id}");
        $response->assertStatus(204);
    }
}
