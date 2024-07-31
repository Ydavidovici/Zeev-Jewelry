<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\Role;
use App\Policies\RolePolicy;
use PHPUnit\Framework\TestCase;

class RolePolicyTest extends TestCase
{
    protected $adminUser;
    protected $sellerUser;
    protected $customerUser;
    protected $role;
    protected $rolePolicy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->make(['role' => 'admin-page']);
        $this->sellerUser = User::factory()->make(['role' => 'seller-page']);
        $this->customerUser = User::factory()->make(['role' => 'customer']);
        $this->role = Role::factory()->make();

        $this->rolePolicy = new RolePolicy();
    }

    public function test_view_any()
    {
        $this->assertTrue($this->rolePolicy->viewAny($this->adminUser));
        $this->assertFalse($this->rolePolicy->viewAny($this->sellerUser));
        $this->assertFalse($this->rolePolicy->viewAny($this->customerUser));
    }

    public function test_view()
    {
        $this->assertTrue($this->rolePolicy->view($this->adminUser, $this->role));
        $this->assertFalse($this->rolePolicy->view($this->sellerUser, $this->role));
        $this->assertFalse($this->rolePolicy->view($this->customerUser, $this->role));
    }

    public function test_create()
    {
        $this->assertTrue($this->rolePolicy->create($this->adminUser));
        $this->assertFalse($this->rolePolicy->create($this->sellerUser));
        $this->assertFalse($this->rolePolicy->create($this->customerUser));
    }

    public function test_update()
    {
        $this->assertTrue($this->rolePolicy->update($this->adminUser, $this->role));
        $this->assertFalse($this->rolePolicy->update($this->sellerUser, $this->role));
        $this->assertFalse($this->rolePolicy->update($this->customerUser, $this->role));
    }

    public function test_delete()
    {
        $this->assertTrue($this->rolePolicy->delete($this->adminUser, $this->role));
        $this->assertFalse($this->rolePolicy->delete($this->sellerUser, $this->role));
        $this->assertFalse($this->rolePolicy->delete($this->customerUser, $this->role));
    }
}
