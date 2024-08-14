<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Policies\SellerPolicy;
use PHPUnit\Framework\TestCase;

class SellerPolicyTest extends TestCase
{
    protected $sellerUser;
    protected $adminUser;
    protected $customerUser;
    protected $sellerPolicy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sellerUser = User::factory()->make(['role' => 'seller']);
        $this->adminUser = User::factory()->make(['role' => 'admin']);
        $this->customerUser = User::factory()->make(['role' => 'customer']);

        $this->sellerPolicy = new SellerPolicy();
    }

    public function test_view_dashboard()
    {
        $this->assertTrue($this->sellerPolicy->viewDashboard($this->sellerUser));
        $this->assertFalse($this->sellerPolicy->viewDashboard($this->adminUser));
        $this->assertFalse($this->sellerPolicy->viewDashboard($this->customerUser));
    }

    public function test_manage_products()
    {
        $this->assertTrue($this->sellerPolicy->manageProducts($this->sellerUser));
        $this->assertFalse($this->sellerPolicy->manageProducts($this->adminUser));
        $this->assertFalse($this->sellerPolicy->manageProducts($this->customerUser));
    }

    public function test_manage_orders()
    {
        $this->assertTrue($this->sellerPolicy->manageOrders($this->sellerUser));
        $this->assertFalse($this->sellerPolicy->manageOrders($this->adminUser));
        $this->assertFalse($this->sellerPolicy->manageOrders($this->customerUser));
    }

    public function test_manage_inventory()
    {
        $this->assertTrue($this->sellerPolicy->manageInventory($this->sellerUser));
        $this->assertFalse($this->sellerPolicy->manageInventory($this->adminUser));
        $this->assertFalse($this->sellerPolicy->manageInventory($this->customerUser));
    }

    public function test_manage_shipping()
    {
        $this->assertTrue($this->sellerPolicy->manageShipping($this->sellerUser));
        $this->assertFalse($this->sellerPolicy->manageShipping($this->adminUser));
        $this->assertFalse($this->sellerPolicy->manageShipping($this->customerUser));
    }

    public function test_manage_payments()
    {
        $this->assertTrue($this->sellerPolicy->managePayments($this->sellerUser));
        $this->assertFalse($this->sellerPolicy->managePayments($this->adminUser));
        $this->assertFalse($this->sellerPolicy->managePayments($this->customerUser));
    }
}
