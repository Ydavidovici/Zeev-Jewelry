<?php

namespace Tests\Feature\Controllers\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordChangeConfirmationMail;

class ChangePasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'RolesAndPermissionsSeeder']); // Seed roles and permissions
    }

    public function test_user_can_change_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);

        $this->actingAs($user, 'api');

        Mail::fake();

        $response = $this->postJson(route('password.update'), [
            'old_password' => 'oldpassword',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Password changed successfully.']);

        Mail::assertSent(PasswordChangeConfirmationMail::class);
    }

    public function test_user_cannot_change_password_with_wrong_old_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);

        $this->actingAs($user, 'api');

        $response = $this->postJson(route('password.update'), [
            'old_password' => 'wrongpassword',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(400)
            ->assertJson(['message' => 'Your current password does not match our records.']);
    }
}
