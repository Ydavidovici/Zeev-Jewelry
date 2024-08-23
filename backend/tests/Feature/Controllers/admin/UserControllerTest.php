<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_users()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin, 'api');

        $response = $this->getJson(route('admin.users.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['users']);
    }

    public function test_admin_can_create_user()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin, 'api');

        $response = $this->postJson(route('admin.users.store'), [
            'username' => 'newuser',
            'password' => 'password',
            'email' => 'newuser@example.com',
            'role_name' => 'admin',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['user']);
    }

    public function test_admin_can_update_user()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $user = User::factory()->create(['username' => 'oldusername', 'email' => 'olduser@example.com']);

        $this->actingAs($admin, 'api');

        $response = $this->putJson(route('admin.users.update', $user), [
            'username' => 'updatedusername',
            'email' => 'updateduser@example.com',
            'role_name' => 'admin',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['user']);
    }

    public function test_admin_can_delete_user()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $user = User::factory()->create();

        $this->actingAs($admin, 'api');

        $response = $this->deleteJson(route('admin.users.destroy', $user));

        $response->assertStatus(204);
    }
}