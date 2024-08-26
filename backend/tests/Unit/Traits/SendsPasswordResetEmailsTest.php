<?php

namespace Tests\Unit\Traits;

use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;


class SendsPasswordResetEmailsTest extends TestCase
{
    use RefreshDatabase;


    public function test_send_reset_link_email()
    {
        // Fake the notifications
        Notification::fake();

        $user = User::factory()->create(['email' => 'test@example.com']);

        $response = $this->post('/password/email', ['email' => 'test@example.com']);

        // Assert the notification was sent
        Notification::assertSentTo(
            [$user], ResetPassword::class
        );

        $response->assertSessionHas('status', trans('passwords.sent'));
    }



    public function test_send_reset_link_email_with_nonexistent_email()
    {
        Mail::fake();

        $response = $this->post('/password/email', ['email' => 'nonexistent@example.com']);

        $response->assertSessionHasErrors(['email']);
    }
}
