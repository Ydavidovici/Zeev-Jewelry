<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_view_registration_form()
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    /** @test */
    public function guest_can_register()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post(route('register'), $data);

        $response->assertRedirect('/admin-page');
        $this->assertDatabaseHas('users', ['email' => 'testuser@example.com']);
        $this->assertAuthenticated();
    }

    /** @test */
    public function guest_cannot_register_with_existing_email()
    {
        $user = User::factory()->create(['email' => 'testuser@example.com']);

        $data = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post(route('register'), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /** @test */
    public function guest_cannot_register_with_unconfirmed_password()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password456',
        ];

        $response = $this->post(route('register'), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }
}
