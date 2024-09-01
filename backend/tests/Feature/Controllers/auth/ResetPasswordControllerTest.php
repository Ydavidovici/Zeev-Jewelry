<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\PasswordChangeConfirmationMail;

class ResetPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_reset_password()
    {
        Mail::fake();

        $user = User::factory()->create(['email' => 'user@example.com']);
        // Generate a password reset token
        $token = Password::broker()->createToken($user);

        $response = $this->postJson(route('password.reset'), [
            'token' => $token,
            'email' => 'user@example.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Password reset successfully.']);

        Mail::assertSent(PasswordChangeConfirmationMail::class);
        $this->assertTrue(Hash::check('newpassword', $user->fresh()->password));
    }

    public function test_password_reset_fails_with_invalid_token()
    {
        $user = User::factory()->create(['email' => 'user@example.com']);

        $response = $this->postJson(route('password.reset'), [
            'token' => 'invalid-token',
            'email' => 'user@example.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(400)
            ->assertJson(['message' => 'This password reset token is invalid.']); // Update expected message here
    }
}
