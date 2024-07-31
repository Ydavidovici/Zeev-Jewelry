<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\OrderDetail;
use App\Policies\OrderDetailPolicy;
use PHPUnit\Framework\TestCase;

class OrderDetailPolicyTest extends TestCase
{
    protected $adminUser;
    protected $sellerUser;
    protected $customerUser;
    protected $orderDetail;
    protected $orderDetailPolicy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->make(['role' => 'admin-page']);
        $this->sellerUser = User::factory()->make(['role' => 'seller-page']);
        $this->customerUser = User::factory()->make(['role' => 'customer']);
        $this->orderDetail = OrderDetail::factory()->make();

        $this->orderDetailPolicy = new OrderDetailPolicy();
    }

    public function test_view_any()
    {
        $this->assertTrue($this->orderDetailPolicy->viewAny($this->adminUser));
        $this->assertTrue($this->orderDetailPolicy->viewAny($this->sellerUser));
        $this->assertTrue($this->orderDetailPolicy->viewAny($this->customerUser));
    }

    public function test_view()
    {
        $this->assertTrue($this->orderDetailPolicy->view($this->adminUser, $this->orderDetail));
        $this->assertTrue($this->orderDetailPolicy->view($this->sellerUser, $this->orderDetail));
        $this->assertTrue($this->orderDetailPolicy->view($this->customerUser, $this->orderDetail));
    }

    public function test_create()
    {
        $this->assertTrue($this->orderDetailPolicy->create($this->adminUser));
        $this->assertTrue($this->orderDetailPolicy->create($this->sellerUser));
        $this->assertTrue($this->orderDetailPolicy->create($this->customerUser));
    }

    public function test_update()
    {
        $this->assertTrue($this->orderDetailPolicy->update($this->adminUser, $this->orderDetail));
        $this->assertTrue($this->orderDetailPolicy->update($this->sellerUser, $this->orderDetail));
        $this->assertFalse($this->orderDetailPolicy->update($this->customerUser, $this->orderDetail));
    }

    public function test_delete()
    {
        $this->assertTrue($this->orderDetailPolicy->delete($this->adminUser, $this->orderDetail));
        $this->assertFalse($this->orderDetailPolicy->delete($this->sellerUser, $this->orderDetail));
        $this->assertFalse($this->orderDetailPolicy->delete($this->customerUser, $this->orderDetail));
    }
}
