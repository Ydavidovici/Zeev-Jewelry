<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Permission;

class PermissionsControllerTest extends TestCase
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
    public function admin_can_view_permissions_index()
    {
        $response = $this->get(route('admin-page.permissions.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.permissions.index');
    }

    /** @test */
    public function admin_can_view_create_permission_form()
    {
        $response = $this->get(route('admin-page.permissions.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.permissions.create');
    }

    /** @test */
    public function admin_can_create_permission()
    {
        $data = [
            'name' => 'test-permission',
        ];

        $response = $this->post(route('admin-page.permissions.store'), $data);

        $response->assertRedirect(route('admin-page.permissions.index'));
        $response->assertSessionHas('success', 'Permission created successfully.');
        $this->assertDatabaseHas('permissions', ['name' => 'test-permission']);
    }

    /** @test */
    public function admin_can_view_edit_permission_form()
    {
        $permission = Permission::factory()->create();

        $response = $this->get(route('admin-page.permissions.edit', $permission));

        $response->assertStatus(200);
        $response->assertViewIs('admin.permissions.edit');
        $response->assertViewHas('permission', $permission);
    }

    /** @test */
    public function admin_can_update_permission()
    {
        $permission = Permission::factory()->create();

        $data = [
            'name' => 'updated-permission',
        ];

        $response = $this->put(route('admin-page.permissions.update', $permission), $data);

        $response->assertRedirect(route('admin-page.permissions.index'));
        $response->assertSessionHas('success', 'Permission updated successfully.');
        $this->assertDatabaseHas('permissions', ['id' => $permission->id, 'name' => 'updated-permission']);
    }

    /** @test */
    public function admin_can_delete_permission()
    {
        $permission = Permission::factory()->create();

        $response = $this->delete(route('admin-page.permissions.destroy', $permission));

        $response->assertRedirect(route('admin-page.permissions.index'));
        $response->assertSessionHas('success', 'Permission deleted successfully.');
        $this->assertDatabaseMissing('permissions', ['id' => $permission->id]);
    }
}
