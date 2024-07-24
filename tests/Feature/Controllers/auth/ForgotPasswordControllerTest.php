<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;

class ForgotPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_view_password_reset_request_form()
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.passwords.email');
    }

    /** @test */
    public function guest_can_request_password_reset_link()
    {
        Notification::fake();

        $user = User::factory()->create(['email' => 'user@example.com']);

        $response = $this->post(route('password.email'), [
            'email' => 'user@example.com',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('status', trans('passwords.sent'));

        Notification::assertSentTo([$user], ResetPassword::class);
    }

    /** @test */
    public function guest_cannot_request_password_reset_link_for_nonexistent_email()
    {
        $response = $this->post(route('password.email'), [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
    }
}
