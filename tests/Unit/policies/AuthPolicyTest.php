<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Policies\AuthPolicy;
use PHPUnit\Framework\TestCase;

class AuthPolicyTest extends TestCase
{
    public function test_change_password()
    {
        $authenticatedUser = User::factory()->make(['isAuthenticated' => true]);
        $guestUser = User::factory()->make(['isAuthenticated' => false]);

        $policy = new AuthPolicy();

        $this->assertTrue($policy->changePassword($authenticatedUser));
        $this->assertFalse($policy->changePassword($guestUser));
    }

    public function test_reset_password()
    {
        $authenticatedUser = User::factory()->make(['isAuthenticated' => true]);
        $guestUser = User::factory()->make(['isAuthenticated' => false]);

        $policy = new AuthPolicy();

        $this->assertTrue($policy->resetPassword($authenticatedUser));
        $this->assertFalse($policy->resetPassword($guestUser));
    }

    public function test_register()
    {
        $user = User::factory()->make();

        $policy = new AuthPolicy();

        $this->assertTrue($policy->register($user));
    }

    public function test_login()
    {
        $user = User::factory()->make();

        $policy = new AuthPolicy();

        $this->assertTrue($policy->login($user));
    }

    public function test_logout()
    {
        $authenticatedUser = User::factory()->make(['isAuthenticated' => true]);
        $guestUser = User::factory()->make(['isAuthenticated' => false]);

        $policy = new AuthPolicy();

        $this->assertTrue($policy->logout($authenticatedUser));
        $this->assertFalse($policy->logout($guestUser));
    }
}
