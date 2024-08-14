<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Role;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_role()
    {
        $data = [
            'name' => 'Admin',
        ];

        $response = $this->post(route('roles.store'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('roles', ['name' => 'Admin']);
    }

    public function test_read_role()
    {
        $role = Role::factory()->create();

        $response = $this->get(route('roles.show', $role->id));

        $response->assertStatus(200);
        $response->assertJson($role->toArray());
    }

    public function test_update_role()
    {
        $role = Role::factory()->create();
        $data = [
            'name' => 'Super Admin',
        ];

        $response = $this->put(route('roles.update', $role->id), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('roles', ['id' => $role->id, 'name' => 'Super Admin']);
    }

    public function test_delete_role()
    {
        $role = Role::factory()->create();
        $roleId = $role->id;

        $response = $this->delete(route('roles.destroy', $roleId));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('roles', ['id' => $roleId]);
    }
}
