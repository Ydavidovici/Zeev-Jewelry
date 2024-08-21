<?php

namespace Tests\Feature\Controllers\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_login_and_receive_token()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token', 'token_type']);

        // You might want to store the access_token to verify it in subsequent tests.
        $accessToken = $response->json('access_token');
        $this->assertNotNull($accessToken);

        // Optionally, make a request to a protected route to ensure the token works
        $this->withHeaders([
            'Authorization' => "Bearer $accessToken",
        ])->get('/some-protected-route')
            ->assertStatus(200);
    }

    public function test_can_logout_and_invalidate_token()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson('/logout')
            ->assertStatus(200)
            ->assertJson(['message' => 'Logged out successfully.']);

        // Try accessing a protected route with the old token
        $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->get('/some-protected-route')
            ->assertStatus(401); // Expecting 401 Unauthorized since the token should be invalidated
    }
}
