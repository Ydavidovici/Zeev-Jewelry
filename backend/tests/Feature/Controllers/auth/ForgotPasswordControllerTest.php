<?php


namespace Tests\Feature\Controllers\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;

class ForgotPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_receives_reset_link_email()
    {
        Mail::fake();

        $user = User::factory()->create();

        $response = $this->postJson(route('password.email'), [
            'email' => $user->email,
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'If the email address is registered, you will receive a reset link.']);

        Mail::assertSent(PasswordResetMail::class);
    }

    public function test_non_registered_email_does_not_receive_reset_link_email()
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
