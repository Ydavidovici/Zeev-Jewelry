<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user and set them as the current authenticated user
        $this->admin = User::factory()->create(['role_id' => 1]); // Assuming role_id 1 is admin
        $this->actingAs($this->admin);
    }

    /** @test */
    public function admin_can_view_roles_index()
    {
        $response = $this->get(route('roles.index'));

        $response->assertStatus(200);
        $response->assertViewIs('roles.index');
    }

    /** @test */
    public function admin_can_view_create_role_form()
    {
        $response = $this->get(route('roles.create'));

        $response->assertStatus(200);
        $response->assertViewIs('roles.create');
    }

    /** @test */
    public function admin_can_create_role()
    {
        $data = [
            'name' => 'test-role',
            'description' => 'This is a test role',
        ];

        $response = $this->post(route('roles.store'), $data);

        $response->assertRedirect(route('roles.index'));
        $this->assertDatabaseHas('roles', ['name' => 'test-role']);
    }

    /** @test */
    public function admin_can_view_edit_role_form()
    {
        $role = Role::factory()->create();

        $response = $this->get(route('roles.edit', $role));

        $response->assertStatus(200);
        $response->assertViewIs('roles.edit');
        $response->assertViewHas('role', $role);
    }

    /** @test */
    public function admin_can_update_role()
    {
        $role = Role::factory()->create();

        $data = [
            'name' => 'updated-role',
            'description' => 'Updated description',
        ];

        $response = $this->put(route('roles.update', $role), $data);

        $response->assertRedirect(route('roles.index'));
        $this->assertDatabaseHas('roles', ['id' => $role->id, 'name' => 'updated-role']);
    }

    /** @test */
    public function admin_can_delete_role()
    {
        $role = Role::factory()->create();

        $response = $this->delete(route('roles.destroy', $role));

        $response->assertRedirect(route('roles.index'));
        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }
}
