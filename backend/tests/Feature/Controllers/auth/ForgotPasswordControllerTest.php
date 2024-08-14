<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ForgotPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_request_password_reset_link()
    {
        $user = User::factory()->create(['email' => 'user@example.com']);

        $response = $this->postJson('/api/password/email', [
            'email' => 'user@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Reset link sent to your email.']);

        $this->assertNotNull(Password::broker()->createToken($user));
    }

    public function test_user_cannot_request_password_reset_link_with_invalid_email()
    {
        $response = $this->postJson('/api/password/email', [
            'email' => 'invalid@example.com',
        ]);

        $response->assertStatus(400)
            ->assertJson(['message' => 'Unable to send reset link.']);
    }
}