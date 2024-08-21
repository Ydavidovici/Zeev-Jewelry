<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\Customer;
use App\Policies\CustomerPolicy;
use PHPUnit\Framework\TestCase;

class CustomerPolicyTest extends TestCase
{
    protected $adminUser;
    protected $sellerUser;
    protected $customerUser;
    protected $otherCustomerUser;
    protected $customer;
    protected $customerPolicy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->make(['role' => 'admin-page']);
        $this->sellerUser = User::factory()->make(['role' => 'seller-page']);
        $this->customerUser = User::factory()->make(['role' => 'customer']);
        $this->otherCustomerUser = User::factory()->make(['role' => 'customer']);
        $this->customer = Customer::factory()->make(['user_id' => $this->customerUser->id]);

        $this->customerPolicy = new CustomerPolicy();
    }

    public function test_view_any()
    {
        $this->assertTrue($this->customerPolicy->viewAny($this->adminUser));
        $this->assertTrue($this->customerPolicy->viewAny($this->sellerUser));
        $this->assertFalse($this->customerPolicy->viewAny($this->customerUser));
    }

    public function test_view()
    {
        $this->assertTrue($this->customerPolicy->view($this->adminUser, $this->customer));
        $this->assertTrue($this->customerPolicy->view($this->sellerUser, $this->customer));
        $this->assertTrue($this->customerPolicy->view($this->customerUser, $this->customer));
        $this->assertFalse($this->customerPolicy->view($this->otherCustomerUser, $this->customer));
    }

    public function test_create()
    {
        $this->assertTrue($this->customerPolicy->create($this->adminUser));
        $this->assertTrue($this->customerPolicy->create($this->sellerUser));
        $this->assertTrue($this->customerPolicy->create($this->customerUser));
        $this->assertFalse($this->customerPolicy->create($this->otherCustomerUser));
    }

    public function test_update()
    {
        $this->assertTrue($this->customerPolicy->update($this->adminUser, $this->customer));
        $this->assertTrue($this->customerPolicy->update($this->sellerUser, $this->customer));
        $this->assertTrue($this->customerPolicy->update($this->customerUser, $this->customer));
        $this->assertFalse($this->customerPolicy->update($this->otherCustomerUser, $this->customer));
    }

    public function test_delete()
    {
        $this->assertTrue($this->customerPolicy->delete($this->adminUser, $this->customer));
        $this->assertTrue($this->customerPolicy->delete($this->customerUser, $this->customer));
        $this->assertFalse($this->customerPolicy->delete($this->sellerUser, $this->customer));
        $this->assertFalse($this->customerPolicy->delete($this->otherCustomerUser, $this->customer));
    }
}
