<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Policies\CustomerPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected $customer;
    protected $nonCustomer;
    protected $policy;

    protected function setUp(): void
    {
        parent::setUp();

        // Clear cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Seed roles and permissions
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

        // Create a customer user
        $this->customer = User::factory()->create();
        $this->customer->assignRole('customer');

        // Create a non-customer user
        $this->nonCustomer = User::factory()->create();
        $this->nonCustomer->assignRole('seller');

        // Instantiate the policy
        $this->policy = new CustomerPolicy();
    }


    public function testCustomerCanManageCart()
    {
        $this->assertTrue($this->policy->manageCart($this->customer));
    }

    public function testNonCustomerCannotManageCart()
    {
        $this->assertFalse($this->policy->manageCart($this->nonCustomer));
    }

    public function testCustomerCanViewOrders()
    {
        $this->assertTrue($this->policy->viewOrders($this->customer));
    }

    public function testNonCustomerCannotViewOrders()
    {
        $this->assertFalse($this->policy->viewOrders($this->nonCustomer));
    }

    public function testCustomerCanManageProfile()
    {
        $this->assertTrue($this->policy->manageProfile($this->customer));
    }

    public function testNonCustomerCannotManageProfile()
    {
        $this->assertFalse($this->policy->manageProfile($this->nonCustomer));
    }

    public function testCustomerCanWriteReview()
    {
        $this->assertTrue($this->policy->writeReview($this->customer));
    }

    public function testNonCustomerCannotWriteReview()
    {
        $this->assertFalse($this->policy->writeReview($this->nonCustomer));
    }

    public function testCustomerCanViewCheckout()
    {
        $this->assertTrue($this->policy->viewCheckout($this->customer));
    }

    public function testNonCustomerCannotViewCheckout()
    {
        $this->assertFalse($this->policy->viewCheckout($this->nonCustomer));
    }

    public function testCustomerCanManageCheckout()
    {
        $this->assertTrue($this->policy->manageCheckout($this->customer));
    }

    public function testNonCustomerCannotManageCheckout()
    {
        $this->assertFalse($this->policy->manageCheckout($this->nonCustomer));
    }

    public function testCustomerCanViewPayments()
    {
        $this->assertTrue($this->policy->viewPayments($this->customer));
    }

    public function testNonCustomerCannotViewPayments()
    {
        $this->assertFalse($this->policy->viewPayments($this->nonCustomer));
    }
}
