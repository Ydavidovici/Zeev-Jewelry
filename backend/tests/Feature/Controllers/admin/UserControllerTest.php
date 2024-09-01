<?php

namespace Tests\Feature\Controllers\Admin;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_users()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin, 'api');

        $response = $this->getJson(route('users.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['users']);
    }

    public function test_non_admin_cannot_view_users()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $response = $this->getJson(route('users.index'));

        $response->assertStatus(403); // Forbidden
    }

    public function test_admin_can_create_user()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin, 'api');

        $response = $this->postJson(route('users.store'), [
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

        $response = $this->postJson(route('users.store'), [
            'username' => 'newuser',
            'password' => 'password123',
            'role_name' => 'admin',
            'email' => 'newuser@example.com',
        ]);

        $response->assertStatus(403); // Forbidden
    }

    public function test_admin_can_update_user()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $user = User::factory()->create();

        $this->actingAs($admin, 'api');

        $response = $this->putJson(route('users.update', $user), [
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

        $response = $this->putJson(route('users.update', $user), [
            'username' => 'updateduser',
            'password' => 'newpassword123',
            'role_name' => 'admin',
            'email' => 'updateduser@example.com',
        ]);

        $response->assertStatus(403); // Forbidden
    }

    public function test_admin_can_delete_user()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $user = User::factory()->create();

        $this->actingAs($admin, 'api');

        $response = $this->deleteJson(route('users.destroy', $user));

        $response->assertStatus(204);
    }

    public function test_non_admin_cannot_delete_user()
    {
        $user = User::factory()->create();
        $targetUser = User::factory()->create();

        $this->actingAs($user, 'api');

        $response = $this->deleteJson(route('users.destroy', $targetUser));

        $response->assertStatus(403); // Forbidden
    }
}
