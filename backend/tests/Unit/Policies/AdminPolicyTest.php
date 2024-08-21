<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Policies\AdminPolicy;
use PHPUnit\Framework\TestCase;

class AdminPolicyTest extends TestCase
{
    public function test_access_dashboard()
    {
        $adminUser = User::factory()->make(['role' => 'admin']);
        $normalUser = User::factory()->make(['role' => 'user']);

        $policy = new AdminPolicy();

        $this->assertTrue($policy->accessDashboard($adminUser));
        $this->assertFalse($policy->accessDashboard($normalUser));
    }

    public function test_manage_users()
    {
        $adminUser = User::factory()->make(['role' => 'admin']);
        $normalUser = User::factory()->make(['role' => 'user']);

        $policy = new AdminPolicy();

        $this->assertTrue($policy->manageUsers($adminUser));
        $this->assertFalse($policy->manageUsers($normalUser));
    }

    public function test_manage_roles()
    {
        $adminUser = User::factory()->make(['role' => 'admin']);
        $normalUser = User::factory()->make(['role' => 'user']);

        $policy = new AdminPolicy();

        $this->assertTrue($policy->manageRoles($adminUser));
        $this->assertFalse($policy->manageRoles($normalUser));
    }

    public function test_manage_permissions()
    {
        $adminUser = User::factory()->make(['role' => 'admin']);
        $normalUser = User::factory()->make(['role' => 'user']);

        $policy = new AdminPolicy();

        $this->assertTrue($policy->managePermissions($adminUser));
        $this->assertFalse($policy->managePermissions($normalUser));
    }

    public function test_manage_settings()
    {
        $adminUser = User::factory()->make(['role' => 'admin']);
        $normalUser = User::factory()->make(['role' => 'user']);

        $policy = new AdminPolicy();

        $this->assertTrue($policy->manageSettings($adminUser));
        $this->assertFalse($policy->manageSettings($normalUser));
    }
}
