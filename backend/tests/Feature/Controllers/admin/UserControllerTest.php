<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_users()
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);

        $response = $this->getJson('/api/admin/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'users'
            ]);
    }

    public function test_admin_can_create_user()
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);

        $response = $this->postJson('/api/admin/users', [
            'username' => 'johndoe',
            'password' => 'secret123',
            'role_id' => 1,
            'email' => 'john@example.com'
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'username' => 'johndoe'
            ]);
    }

    public function test_admin_can_update_user()
    {
        $admin = User::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($admin);

        $response = $this->putJson("/api/admin/users/{$user->id}", [
            'username' => 'johnupdated',
            'email' => 'johnupdated@example.com',
            'role_id' => 1
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'username' => 'johnupdated'
            ]);
    }

    public function test_admin_can_delete_user()
    {
        $admin = User::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($admin);

        $response = $this->deleteJson("/api/admin/users/{$user->id}");

        $response->assertStatus(204);
    }
}
