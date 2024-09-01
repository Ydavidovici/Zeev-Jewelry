<?php

namespace Tests\Feature\Controllers\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        Mail::fake();

        $response = $this->postJson(route('auth.register'), [
            'username' => 'johndoe',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'access_token',
                'token_type',
            ]);

        Mail::assertSent(WelcomeMail::class);
    }

    public function test_user_cannot_register_with_existing_email()
    {
        User::factory()->create(['email' => 'john@example.com']);

        $response = $this->postJson(route('auth.register'), [
            'username' => 'johndoe',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['errors']);
    }
}
