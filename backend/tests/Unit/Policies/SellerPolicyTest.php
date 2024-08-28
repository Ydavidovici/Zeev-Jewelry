<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Policies\SellerPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected $seller;
    protected $nonSeller;
    protected $policy;

    protected function setUp(): void
    {
        parent::setUp();

        // Clear cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Seed roles and permissions
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

        // Create a seller user
        $this->seller = User::factory()->create();
        $this->seller->assignRole('seller');

        // Create a non-seller user
        $this->nonSeller = User::factory()->create();
        $this->nonSeller->assignRole('customer');

        // Instantiate the policy
        $this->policy = new SellerPolicy();
    }


    public function testSellerCanManageProducts()
    {
        $this->assertTrue($this->policy->manageProducts($this->seller));
    }

    public function testNonSellerCannotManageProducts()
    {
        $this->assertFalse($this->policy->manageProducts($this->nonSeller));
    }

    public function testSellerCanManageOrders()
    {
        $this->assertTrue($this->policy->manageOrders($this->seller));
    }

    public function testNonSellerCannotManageOrders()
    {
        $this->assertFalse($this->policy->manageOrders($this->nonSeller));
    }

    public function testSellerCanManageInventory()
    {
        $this->assertTrue($this->policy->manageInventory($this->seller));
    }

    public function testNonSellerCannotManageInventory()
    {
        $this->assertFalse($this->policy->manageInventory($this->nonSeller));
    }

    public function testSellerCanManageShipping()
    {
        $this->assertTrue($this->policy->manageShipping($this->seller));
    }

    public function testNonSellerCannotManageShipping()
    {
        $this->assertFalse($this->policy->manageShipping($this->nonSeller));
    }

    public function testSellerCanManagePayments()
    {
        $this->assertTrue($this->policy->managePayments($this->seller));
    }

    public function testNonSellerCannotManagePayments()
    {
        $this->assertFalse($this->policy->managePayments($this->nonSeller));
    }

    public function testSellerCanManageReports()
    {
        $this->assertTrue($this->policy->manageReports($this->seller));
    }

    public function testNonSellerCannotManageReports()
    {
        $this->assertFalse($this->policy->manageReports($this->nonSeller));
    }

    public function testSellerCanUploadFiles()
    {
        $this->assertTrue($this->policy->uploadFiles($this->seller));
    }

    public function testNonSellerCannotUploadFiles()
    {
        $this->assertFalse($this->policy->uploadFiles($this->nonSeller));
    }
}