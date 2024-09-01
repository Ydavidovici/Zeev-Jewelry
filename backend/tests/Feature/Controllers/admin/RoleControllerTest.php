<?php

namespace Tests\Feature\Controllers\Admin;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_roles()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin, 'api');

        $response = $this->getJson(route('roles.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['roles']);
    }

    public function test_non_admin_cannot_view_roles()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $response = $this->getJson(route('roles.index'));

        $response->assertStatus(403); // Forbidden
    }

    public function test_admin_can_create_role()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin, 'api');

        $response = $this->postJson(route('roles.store'), [
            'name' => 'editor',
            'description' => 'Can edit content',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['role']);
    }

    public function test_non_admin_cannot_create_role()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $response = $this->postJson(route('roles.store'), [
            'name' => 'editor',
            'description' => 'Can edit content',
        ]);

        $response->assertStatus(403); // Forbidden
    }

    public function test_admin_can_update_role()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $role = Role::create(['name' => 'editor', 'description' => 'Can edit content']);

        $this->actingAs($admin, 'api');

        $response = $this->putJson(route('roles.update', $role), [
            'name' => 'super editor',
            'description' => 'Can edit all content',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['role']);
    }

    public function test_non_admin_cannot_update_role()
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'editor', 'description' => 'Can edit content']);

        $this->actingAs($user, 'api');

        $response = $this->putJson(route('roles.update', $role), [
            'name' => 'super editor',
            'description' => 'Can edit all content',
        ]);

        $response->assertStatus(403); // Forbidden
    }

    public function test_admin_can_delete_role()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $role = Role::create(['name' => 'editor', 'description' => 'Can edit content']);

        $this->actingAs($admin, 'api');

        $response = $this->deleteJson(route('roles.destroy', $role));

        $response->assertStatus(204);
    }

    public function test_non_admin_cannot_delete_role()
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'editor', 'description' => 'Can edit content']);

        $this->actingAs($user, 'api');

        $response = $this->deleteJson(route('roles.destroy', $role));

        $response->assertStatus(403); // Forbidden
    }
}
