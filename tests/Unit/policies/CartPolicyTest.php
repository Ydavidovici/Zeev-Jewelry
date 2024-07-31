<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\Cart;
use App\Policies\CartPolicy;
use PHPUnit\Framework\TestCase;

class CartPolicyTest extends TestCase
{
    protected $adminUser;
    protected $customerUser;
    protected $otherCustomerUser;
    protected $cart;
    protected $cartPolicy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->make(['role' => 'admin']);
        $this->customerUser = User::factory()->make(['role' => 'customer']);
        $this->otherCustomerUser = User::factory()->make(['role' => 'customer']);
        $this->cart = Cart::factory()->make(['user_id' => $this->customerUser->id]);

        $this->cartPolicy = new CartPolicy();
    }

    public function test_view_any()
    {
        $this->assertTrue($this->cartPolicy->viewAny($this->adminUser));
        $this->assertTrue($this->cartPolicy->viewAny($this->customerUser));
        $this->assertFalse($this->cartPolicy->viewAny($this->otherCustomerUser));
    }

    public function test_view()
    {
        $this->assertTrue($this->cartPolicy->view($this->customerUser, $this->cart));
        $this->assertTrue($this->cartPolicy->view($this->adminUser, $this->cart));
        $this->assertFalse($this->cartPolicy->view($this->otherCustomerUser, $this->cart));
    }

    public function test_create()
    {
        $this->assertTrue($this->cartPolicy->create($this->customerUser));
        $this->assertFalse($this->cartPolicy->create($this->adminUser));
    }

    public function test_update()
    {
        $this->assertTrue($this->cartPolicy->update($this->customerUser, $this->cart));
        $this->assertTrue($this->cartPolicy->update($this->adminUser, $this->cart));
        $this->assertFalse($this->cartPolicy->update($this->otherCustomerUser, $this->cart));
    }

    public function test_delete()
    {
        $this->assertTrue($this->cartPolicy->delete($this->customerUser, $this->cart));
        $this->assertTrue($this->cartPolicy->delete($this->adminUser, $this->cart));
        $this->assertFalse($this->cartPolicy->delete($this->otherCustomerUser, $this->cart));
    }
}
