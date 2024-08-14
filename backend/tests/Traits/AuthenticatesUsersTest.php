<?php

namespace Tests\Unit\Traits;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticatesUsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_login()
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin-page');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_with_invalid_credentials()
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_logout()
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/'); // Ensure this is the correct redirect URL after logout
        $this->assertGuest();
    }
}
