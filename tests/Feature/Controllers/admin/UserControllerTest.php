<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;

class UserControllerTest extends TestCase
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
    public function admin_can_view_users_index()
    {
        $response = $this->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertViewIs('users.index');
    }

    /** @test */
    public function admin_can_view_create_user_form()
    {
        $response = $this->get(route('users.create'));

        $response->assertStatus(200);
        $response->assertViewIs('users.create');
    }

    /** @test */
    public function admin_can_create_user()
    {
        $role = Role::factory()->create();

        $data = [
            'username' => 'testuser',
            'password' => 'password', // Raw password to validate
            'role_id' => $role->id,
            'email' => 'test@example.com',
        ];

        $response = $this->post(route('users.store'), $data);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    /** @test */
    public function admin_can_view_edit_user_form()
    {
        $user = User::factory()->create();

        $response = $this->get(route('users.edit', $user));

        $response->assertStatus(200);
        $response->assertViewIs('users.edit');
        $response->assertViewHas('user', $user);
    }

    /** @test */
    public function admin_can_update_user()
    {
        $user = User::factory()->create();
        $role = Role::factory()->create();

        $data = [
            'username' => 'updateduser',
            'role_id' => $role->id,
            'email' => 'updated@example.com',
        ];

        $response = $this->put(route('users.update', $user), $data);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['id' => $user->id, 'username' => 'updateduser']);
    }

    /** @test */
    public function admin_can_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->delete(route('users.destroy', $user));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
