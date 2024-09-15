<?php

namespace Tests\Feature\Controllers\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;
use App\Models\User;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure the 'admin' role and 'manage roles' permission exist
        if (!Role::where('name', 'admin')->exists()) {
            $adminRole = Role::create(['name' => 'admin']);
        } else {
            $adminRole = Role::where('name', 'admin')->first();
        }

        // Ensure the 'manage roles' permission exists and assign it to the 'admin' role
        if (!Permission::where('name', 'manage roles')->exists()) {
            $manageRolesPermission = Permission::create(['name' => 'manage roles']);
            $adminRole->givePermissionTo($manageRolesPermission);
        }
    }

    public function test_admin_can_view_roles()
    {
        // Create a user and assign the 'admin' role
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // Act as the admin and check if they can view roles
        $this->actingAs($admin, 'api')
            ->getJson(route('admin.roles.index'))
            ->assertStatus(200)
            ->assertJsonStructure(['roles']);
    }

    public function test_non_admin_cannot_view_roles()
    {
        // Create a non-admin user
        $user = User::factory()->create();

        // Try to view roles as a non-admin user
        $this->actingAs($user, 'api')
            ->getJson(route('admin.roles.index'))
            ->assertStatus(403);
    }

    public function test_admin_can_create_role()
    {
        // Create a user and assign the 'admin' role
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // Act as the admin and check if they can create a new role
        $this->actingAs($admin, 'api')
            ->postJson(route('admin.roles.store'), ['name' => 'editor'])
            ->assertStatus(201)
            ->assertJson(['role' => ['name' => 'editor']]);
    }

    public function test_non_admin_cannot_create_role()
    {
        // Create a non-admin user
        $user = User::factory()->create();

        // Try to create a role as a non-admin user
        $this->actingAs($user, 'api')
            ->postJson(route('admin.roles.store'), ['name' => 'editor'])
            ->assertStatus(403);
    }
}
