<?php


namespace Tests\Unit\Traits;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetsPasswordsTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);
        $token = Password::createToken($user);

        $response = $this->post('/password/reset', [
            'token' => $token,
            'email' => 'test@example.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertRedirect('/admin-page');
        $this->assertTrue(Hash::check('newpassword', $user->fresh()->password));
    }

    public function test_reset_password_with_invalid_token()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        $response = $this->post('/password/reset', [
            'token' => 'invalid-token',
            'email' => 'test@example.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertSessionHasErrors('email');
    }
}
