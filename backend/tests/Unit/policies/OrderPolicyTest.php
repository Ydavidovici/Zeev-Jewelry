<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\Order;
use App\Policies\OrderPolicy;
use PHPUnit\Framework\TestCase;

class OrderPolicyTest extends TestCase
{
    protected $adminUser;
    protected $sellerUser;
    protected $customerUser;
    protected $order;
    protected $orderPolicy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->make(['role' => 'admin-page']);
        $this->sellerUser = User::factory()->make(['role' => 'seller-page']);
        $this->customerUser = User::factory()->make(['role' => 'customer']);
        $this->order = Order::factory()->make();

        $this->orderPolicy = new OrderPolicy();
    }

    public function test_view_any()
    {
        $this->assertTrue($this->orderPolicy->viewAny($this->adminUser));
        $this->assertTrue($this->orderPolicy->viewAny($this->sellerUser));
        $this->assertTrue($this->orderPolicy->viewAny($this->customerUser));
    }

    public function test_view()
    {
        $this->assertTrue($this->orderPolicy->view($this->adminUser, $this->order));
        $this->assertTrue($this->orderPolicy->view($this->sellerUser, $this->order));
        $this->assertTrue($this->orderPolicy->view($this->customerUser, $this->order));
    }

    public function test_create()
    {
        $this->assertTrue($this->orderPolicy->create($this->adminUser));
        $this->assertTrue($this->orderPolicy->create($this->sellerUser));
        $this->assertTrue($this->orderPolicy->create($this->customerUser));
    }

    public function test_update()
    {
        $this->assertTrue($this->orderPolicy->update($this->adminUser, $this->order));
        $this->assertTrue($this->orderPolicy->update($this->sellerUser, $this->order));
        $this->assertFalse($this->orderPolicy->update($this->customerUser, $this->order));
    }

    public function test_delete()
    {
        $this->assertTrue($this->orderPolicy->delete($this->adminUser, $this->order));
        $this->assertFalse($this->orderPolicy->delete($this->sellerUser, $this->order));
        $this->assertFalse($this->orderPolicy->delete($this->customerUser, $this->order));
    }
}
