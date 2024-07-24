<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class ChangePasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and set them as the current authenticated user
        $this->user = User::factory()->create(['password' => Hash::make('oldpassword')]);
        $this->actingAs($this->user);
    }

    /** @test */
    public function user_can_view_change_password_form()
    {
        $response = $this->get(route('password.change'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.change-password');
    }

    /** @test */
    public function user_can_change_password_with_correct_old_password()
    {
        $data = [
            'old_password' => 'oldpassword',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'newpassword',
        ];

        $response = $this->post(route('password.update'), $data);

        $response->assertRedirect(route('profile'));
        $response->assertSessionHas('success', 'Password changed successfully.');
        $this->assertTrue(Hash::check('newpassword', $this->user->fresh()->password));
    }

    /** @test */
    public function user_cannot_change_password_with_incorrect_old_password()
    {
        $data = [
            'old_password' => 'wrongpassword',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'newpassword',
        ];

        $response = $this->post(route('password.update'), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['old_password' => 'Your current password does not match our records.']);
        $this->assertTrue(Hash::check('oldpassword', $this->user->fresh()->password));
    }

    /** @test */
    public function user_cannot_change_password_with_unconfirmed_new_password()
    {
        $data = [
            'old_password' => 'oldpassword',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'differentpassword',
        ];

        $response = $this->post(route('password.update'), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['new_password' => 'The new password confirmation does not match.']);
        $this->assertTrue(Hash::check('oldpassword', $this->user->fresh()->password));
    }
}
