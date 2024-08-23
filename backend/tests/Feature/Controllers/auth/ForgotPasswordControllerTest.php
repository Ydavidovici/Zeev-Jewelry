<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;

class ForgotPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_link_email_is_sent_when_user_exists()
    {
        Mail::fake();

        $user = User::factory()->create(['email' => 'user@example.com']);

        $response = $this->postJson(route('password.email'), [
            'email' => 'user@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'If the email address is registered, you will receive a reset link.']);

        Mail::assertSent(PasswordResetMail::class);
    }

    public function test_no_email_sent_when_user_does_not_exist()
    {
        Mail::fake();

        $response = $this->postJson(route('password.email'), [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'If the email address is registered, you will receive a reset link.']);

        Mail::assertNotSent(PasswordResetMail::class);
    }
}
