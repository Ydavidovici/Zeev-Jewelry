<?php

namespace Tests\Feature\Controllers\admin;

use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_permissions()
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);

        $response = $this->getJson('/api/admin/permissions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'permissions'
            ]);
    }

    public function test_admin_can_create_permission()
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);

        $response = $this->postJson('/api/admin/permissions', [
            'name' => 'edit posts'
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'edit posts'
            ]);
    }

    public function test_admin_can_update_permission()
    {
        $admin = User::factory()->create();
        $permission = Permission::create(['name' => 'edit posts']);
        $this->actingAs($admin);

        $response = $this->putJson("/api/admin/permissions/{$permission->id}", [
            'name' => 'edit articles'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'edit articles'
            ]);
    }

    public function test_admin_can_delete_permission()
    {
        $admin = User::factory()->create();
        $permission = Permission::create(['name' => 'edit posts']);
        $this->actingAs($admin);

        $response = $this->deleteJson("/api/admin/permissions/{$permission->id}");

        $response->assertStatus(204);
    }
}
