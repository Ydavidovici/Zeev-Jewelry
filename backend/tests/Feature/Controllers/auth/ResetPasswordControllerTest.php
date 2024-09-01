<?php

namespace Tests\Feature\Controllers\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use App\Mail\PasswordChangeConfirmationMail;

class ResetPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_reset_password()
    {
        Mail::fake();

        $user = User::factory()->create();

        $token = Password::createToken($user);

        $response = $this->postJson(route('auth.resetPassword'), [
            'email' => $user->email,
            'token' => $token,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Password reset successfully.']);

        Mail::assertSent(PasswordChangeConfirmationMail::class);
    }

    public function test_user_cannot_reset_password_with_invalid_token()
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('auth.resetPassword'), [
            'email' => $user->email,
            'token' => 'invalidtoken',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(400)
            ->assertJson(['message' => 'This password reset token is invalid.']);
    }
}
