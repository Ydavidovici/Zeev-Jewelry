<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\Checkout;
use App\Policies\CheckoutPolicy;
use PHPUnit\Framework\TestCase;

class CheckoutPolicyTest extends TestCase
{
    protected $adminUser;
    protected $customerUser;
    protected $otherCustomerUser;
    protected $checkout;
    protected $checkoutPolicy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->make(['role' => 'admin']);
        $this->customerUser = User::factory()->make(['role' => 'customer']);
        $this->otherCustomerUser = User::factory()->make(['role' => 'customer']);
        $this->checkout = Checkout::factory()->make(['user_id' => $this->customerUser->id]);

        $this->checkoutPolicy = new CheckoutPolicy();
    }

    public function test_view_any()
    {
        $this->assertTrue($this->checkoutPolicy->viewAny($this->adminUser));
        $this->assertTrue($this->checkoutPolicy->viewAny($this->customerUser));
        $this->assertFalse($this->checkoutPolicy->viewAny($this->otherCustomerUser));
    }

    public function test_view()
    {
        $this->assertTrue($this->checkoutPolicy->view($this->customerUser, $this->checkout));
        $this->assertTrue($this->checkoutPolicy->view($this->adminUser, $this->checkout));
        $this->assertFalse($this->checkoutPolicy->view($this->otherCustomerUser, $this->checkout));
    }

    public function test_create()
    {
        $this->assertTrue($this->checkoutPolicy->create($this->customerUser));
        $this->assertFalse($this->checkoutPolicy->create($this->adminUser));
    }

    public function test_update()
    {
        $this->assertTrue($this->checkoutPolicy->update($this->customerUser, $this->checkout));
        $this->assertTrue($this->checkoutPolicy->update($this->adminUser, $this->checkout));
        $this->assertFalse($this->checkoutPolicy->update($this->otherCustomerUser, $this->checkout));
    }

    public function test_delete()
    {
        $this->assertTrue($this->checkoutPolicy->delete($this->customerUser, $this->checkout));
        $this->assertTrue($this->checkoutPolicy->delete($this->adminUser, $this->checkout));
        $this->assertFalse($this->checkoutPolicy->delete($this->otherCustomerUser, $this->checkout));
    }
}
