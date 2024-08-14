<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user()
    {
        $data = [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('secret'),
            'role' => 'customer',
        ];

        $response = $this->post(route('users.store'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_read_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('users.show', $user->id));

        $response->assertStatus(200);
        $response->assertJson($user->toArray());
    }

    public function test_update_user()
    {
        $user = User::factory()->create();
        $data = [
            'username' => 'updateduser',
            'email' => 'updated@example.com',
        ];

        $response = $this->actingAs($user)->put(route('users.update', $user->id), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'username' => 'updateduser']);
    }

    public function test_delete_user()
    {
        $user = User::factory()->create();
        $userId = $user->id;

        $response = $this->actingAs($user)->delete(route('users.destroy', $userId));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }
}
