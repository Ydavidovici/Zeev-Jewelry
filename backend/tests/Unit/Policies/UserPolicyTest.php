<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Policies\UserPolicy;
use PHPUnit\Framework\TestCase;

class UserPolicyTest extends TestCase
{
    protected $adminUser;
    protected $sellerUser;
    protected $customerUser;
    protected $modelUser;
    protected $userPolicy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->make(['role' => 'admin-page']);
        $this->sellerUser = User::factory()->make(['role' => 'seller-page']);
        $this->customerUser = User::factory()->make(['role' => 'customer']);
        $this->modelUser = User::factory()->make();

        $this->userPolicy = new UserPolicy();
    }

    public function test_view_any()
    {
        $this->assertTrue($this->userPolicy->viewAny($this->adminUser));
        $this->assertFalse($this->userPolicy->viewAny($this->sellerUser));
        $this->assertFalse($this->userPolicy->viewAny($this->customerUser));
    }

    public function test_view()
    {
        $this->assertTrue($this->userPolicy->view($this->adminUser, $this->modelUser));
        $this->assertFalse($this->userPolicy->view($this->sellerUser, $this->modelUser));
        $this->assertFalse($this->userPolicy->view($this->customerUser, $this->modelUser));
    }

    public function test_create()
    {
        $this->assertTrue($this->userPolicy->create($this->adminUser));
        $this->assertFalse($this->userPolicy->create($this->sellerUser));
        $this->assertFalse($this->userPolicy->create($this->customerUser));
    }

    public function test_update()
    {
        $this->assertTrue($this->userPolicy->update($this->adminUser, $this->modelUser));
        $this->assertFalse($this->userPolicy->update($this->sellerUser, $this->modelUser));
        $this->assertFalse($this->userPolicy->update($this->customerUser, $this->modelUser));
    }

    public function test_delete()
    {
        $this->assertTrue($this->userPolicy->delete($this->adminUser, $this->modelUser));
        $this->assertFalse($this->userPolicy->delete($this->sellerUser, $this->modelUser));
        $this->assertFalse($this->userPolicy->delete($this->customerUser, $this->modelUser));
    }
}
