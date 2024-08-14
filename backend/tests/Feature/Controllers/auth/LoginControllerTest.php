<?php

namespace Tests\Feature\Controllers\auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_login_and_set_remember_me_token()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'password',
            'remember' => true,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token', 'token_type']);

        $this->assertNotNull($user->fresh()->remember_token);
        $this->assertEquals($user->remember_token, $this->getCookie('remember_token'));
    }

    public function test_can_logout_and_clear_remember_me_token()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $this->actingAs($user, 'sanctum')
            ->postJson('/logout')
            ->assertStatus(200)
            ->assertJson(['message' => 'Logged out successfully.']);

        $this->assertNull($user->fresh()->remember_token);
    }
}
