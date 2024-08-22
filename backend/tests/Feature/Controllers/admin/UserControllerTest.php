<?php

namespace Tests\Feature\Controllers\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles and permissions with the 'api' guard
        $manageUsersPermission = Permission::firstOrCreate(['name' => 'manageUsers', 'guard_name' => 'api']);
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        $adminRole->givePermissionTo($manageUsersPermission);

        // Create a customer role with the 'api' guard
        $customerRole = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'api']);

        // Create an admin user and assign the role without specifying the guard (it will use the correct guard based on the role)
        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole($adminRole);

        // Authenticate as admin using sanctum (which typically uses the 'api' guard)
        $this->actingAs($this->adminUser, 'sanctum');
    }

    public function test_admin_can_view_users()
    {
        $user = User::factory()->create();

        $response = $this->getJson(route('admin.users.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'users' => [
                    '*' => ['id', 'username', 'email', 'created_at', 'updated_at']
                ]
            ]);
    }

    public function test_admin_can_create_user()
    {
        $response = $this->postJson(route('admin.users.store'), [
            'username' => 'johndoe',
            'password' => 'secret123',
            'role_name' => 'customer', // Ensure the 'customer' role exists for 'api' guard
            'email' => 'john@example.com'
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'username' => 'johndoe',
                'email' => 'john@example.com'
            ]);
    }

    public function test_admin_can_update_user()
    {
        $user = User::factory()->create();

        $response = $this->putJson(route('admin.users.update', $user->id), [
            'username' => 'johnupdated',
            'email' => 'johnupdated@example.com',
            'role_name' => 'customer' // Ensure the 'customer' role exists for 'api' guard
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'username' => 'johnupdated',
                'email' => 'johnupdated@example.com'
            ]);
    }

    public function test_admin_can_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson(route('admin.users.destroy', $user->id));

        $response->assertStatus(204);
    }

    public function test_non_admin_cannot_manage_users()
    {
        $nonAdminUser = User::factory()->create();
        $this->actingAs($nonAdminUser, 'sanctum');

        // Attempt to create a user
        $response = $this->postJson(route('admin.users.store'), [
            'username' => 'johndoe',
            'password' => 'secret123',
            'role_name' => 'customer',
            'email' => 'john@example.com'
        ]);

        $response->assertStatus(403);

        // Attempt to view users
        $response = $this->getJson(route('admin.users.index'));

        $response->assertStatus(403);
    }
}
