<?php

namespace Tests\Feature\Controllers\admin;

use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_roles()
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);

        $response = $this->getJson('/api/admin/roles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'roles'
            ]);
    }

    public function test_admin_can_create_role()
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);

        $response = $this->postJson('/api/admin/roles', [
            'name' => 'editor',
            'description' => 'Editor Role'
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'editor'
            ]);
    }

    public function test_admin_can_update_role()
    {
        $admin = User::factory()->create();
        $role = Role::create(['name' => 'editor', 'description' => 'Editor Role']);
        $this->actingAs($admin);

        $response = $this->putJson("/api/admin/roles/{$role->id}", [
            'name' => 'super editor',
            'description' => 'Super Editor Role'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'super editor'
            ]);
    }

    public function test_admin_can_delete_role()
    {
        $admin = User::factory()->create();
        $role = Role::create(['name' => 'editor', 'description' => 'Editor Role']);
        $this->actingAs($admin);

        $response = $this->deleteJson("/api/admin/roles/{$role->id}");

        $response->assertStatus(204);
    }
}
