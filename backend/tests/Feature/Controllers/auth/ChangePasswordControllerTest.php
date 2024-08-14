<?php

namespace Tests\Feature\Controllers\auth;

use App\Mail\PasswordChangeConfirmationMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ChangePasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_change_password()
    {
        Mail::fake();

        $user = User::factory()->create([
            'password' => bcrypt('oldpassword123')
        ]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/password/change', [
            'old_password' => 'oldpassword123',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Password changed successfully.']);

        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));

        Mail::assertSent(PasswordChangeConfirmationMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email) && $mail->user->is($user);
        });
    }

    public function test_user_cannot_change_password_with_wrong_old_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('oldpassword123')
        ]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/password/change', [
            'old_password' => 'wrongpassword',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(400)
            ->assertJson(['message' => 'Your current password does not match our records.']);
    }
}
