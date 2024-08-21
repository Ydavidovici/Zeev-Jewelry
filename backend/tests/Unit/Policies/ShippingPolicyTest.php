<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\Shipping;
use App\Policies\ShippingPolicy;
use PHPUnit\Framework\TestCase;

class ShippingPolicyTest extends TestCase
{
    protected $adminUser;
    protected $sellerUser;
    protected $customerUser;
    protected $shipping;
    protected $shippingPolicy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->make(['role' => 'admin-page']);
        $this->sellerUser = User::factory()->make(['role' => 'seller-page']);
        $this->customerUser = User::factory()->make(['role' => 'customer']);
        $this->shipping = Shipping::factory()->make();

        $this->shippingPolicy = new ShippingPolicy();
    }

    public function test_view_any()
    {
        $this->assertTrue($this->shippingPolicy->viewAny($this->adminUser));
        $this->assertTrue($this->shippingPolicy->viewAny($this->sellerUser));
        $this->assertFalse($this->shippingPolicy->viewAny($this->customerUser));
    }

    public function test_view()
    {
        $this->assertTrue($this->shippingPolicy->view($this->adminUser, $this->shipping));
        $this->assertTrue($this->shippingPolicy->view($this->sellerUser, $this->shipping));
        $this->assertFalse($this->shippingPolicy->view($this->customerUser, $this->shipping));
    }

    public function test_create()
    {
        $this->assertTrue($this->shippingPolicy->create($this->adminUser));
        $this->assertTrue($this->shippingPolicy->create($this->sellerUser));
        $this->assertFalse($this->shippingPolicy->create($this->customerUser));
    }

    public function test_update()
    {
        $this->assertTrue($this->shippingPolicy->update($this->adminUser, $this->shipping));
        $this->assertTrue($this->shippingPolicy->update($this->sellerUser, $this->shipping));
        $this->assertFalse($this->shippingPolicy->update($this->customerUser, $this->shipping));
    }

    public function test_delete()
    {
        $this->assertTrue($this->shippingPolicy->delete($this->adminUser, $this->shipping));
        $this->assertFalse($this->shippingPolicy->delete($this->sellerUser, $this->shipping));
        $this->assertFalse($this->shippingPolicy->delete($this->customerUser, $this->shipping));
    }
}
