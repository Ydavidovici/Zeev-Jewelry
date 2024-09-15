<?php

namespace Tests\Feature\Controllers\Admin;

use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure 'admin' role is created and assign 'manage users' permission
        if (!Role::where('name', 'admin')->exists()) {
            $adminRole = Role::create(['name' => 'admin']);
        } else {
            $adminRole = Role::where('name', 'admin')->first();
        }

        if (!Permission::where('name', 'manage users')->exists()) {
            $manageUsersPermission = Permission::create(['name' => 'manage users']);
            $adminRole->givePermissionTo($manageUsersPermission);
        }
    }

    public function test_admin_can_view_users()
    {
        // Create an admin user and assign the 'admin' role with 'manage users' permission
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin, 'api');

        $response = $this->getJson(route('admin.users.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['users']);
    }

    public function test_non_admin_cannot_view_users()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $response = $this->getJson(route('admin.users.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_create_user()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin, 'api');

        $response = $this->postJson(route('admin.users.store'), [
            'username' => 'newuser',
            'password' => 'password123',
            'role_name' => 'admin',
            'email' => 'newuser@example.com',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['user']);
    }

    public function test_non_admin_cannot_create_user()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $response = $this->postJson(route('admin.users.store'), [
            'username' => 'newuser',
            'password' => 'password123',
            'role_name' => 'admin',
            'email' => 'newuser@example.com',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_update_user()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $user = User::factory()->create();

        $this->actingAs($admin, 'api');

        $response = $this->putJson(route('admin.users.update', $user), [
            'username' => 'updateduser',
            'password' => 'newpassword123',
            'role_name' => 'admin',
            'email' => 'updateduser@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['user']);
    }

    public function test_non_admin_cannot_update_user()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $response = $this->putJson(route('admin.users.update', $user), [
            'username' => 'updateduser',
            'password' => 'newpassword123',
            'role_name' => 'admin',
            'email' => 'updateduser@example.com',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_delete_user()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $user = User::factory()->create();

        $this->actingAs($admin, 'api');

        $response = $this->deleteJson(route('admin.users.destroy', $user));

        $response->assertStatus(204);
    }

    public function test_non_admin_cannot_delete_user()
    {
        $user = User::factory()->create();
        $targetUser = User::factory()->create();

        $this->actingAs($user, 'api');

        $response = $this->deleteJson(route('admin.users.destroy', $targetUser));

        $response->assertStatus(403);
    }
}
