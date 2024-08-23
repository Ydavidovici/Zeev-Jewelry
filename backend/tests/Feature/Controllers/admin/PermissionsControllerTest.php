<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_permissions()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin, 'api');

        $response = $this->getJson(route('admin.permissions.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['permissions']);
    }

    public function test_non_admin_cannot_view_permissions()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $response = $this->getJson(route('admin.permissions.index'));

        $response->assertStatus(403); // Forbidden
    }

    public function test_admin_can_create_permission()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->actingAs($admin, 'api');

        $response = $this->postJson(route('admin.permissions.store'), [
            'name' => 'edit articles',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['permission']);
    }

    public function test_admin_can_update_permission()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $permission = Permission::create(['name' => 'edit articles']);

        $this->actingAs($admin, 'api');

        $response = $this->putJson(route('admin.permissions.update', $permission), [
            'name' => 'edit posts',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['permission']);
    }

    public function test_admin_can_delete_permission()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $permission = Permission::create(['name' => 'edit articles']);

        $this->actingAs($admin, 'api');

        $response = $this->deleteJson(route('admin.permissions.destroy', $permission));

        $response->assertStatus(204);
    }
}
