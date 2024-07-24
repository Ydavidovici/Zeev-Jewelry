<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;
use App\Models\User;

class ResetPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_view_password_reset_form()
    {
        $token = Password::createToken(User::factory()->create());

        $response = $this->get(route('password.reset', ['token' => $token]));

        $response->assertStatus(200);
        $response->assertViewIs('auth.passwords.reset');
    }

    /** @test */
    public function guest_can_reset_password()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('oldpassword'),
        ]);

        $token = Password::createToken($user);

        $data = [
            'email' => 'user@example.com',
            'token' => $token,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ];

        $response = $this->post(route('password.update'), $data);

        $response->assertRedirect('/admin-page');
        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    /** @test */
    public function guest_cannot_reset_password_with_invalid_token()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('oldpassword'),
        ]);

        $data = [
            'email' => 'user@example.com',
            'token' => 'invalid-token',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ];

        $response = $this->post(route('password.update'), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email']);
        $this->assertTrue(Hash::check('oldpassword', $user->fresh()->password));
    }
}
