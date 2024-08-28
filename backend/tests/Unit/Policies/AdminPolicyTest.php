<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Policies\AdminPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $nonAdmin;
    protected $policy;


    protected function setUp(): void
    {
        parent::setUp();

        // Clear cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Seed roles and permissions
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

        // Create an admin user
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        // Create a non-admin user
        $this->nonAdmin = User::factory()->create();
        $this->nonAdmin->assignRole('customer');

        // Instantiate the policy
        $this->policy = new AdminPolicy();
    }

    public function testAdminCanAccessDashboard()
    {
        $this->assertTrue($this->policy->accessDashboard($this->admin));
    }

    public function testNonAdminCannotAccessDashboard()
    {
        $this->assertFalse($this->policy->accessDashboard($this->nonAdmin));
    }

    public function testAdminCanManageUsers()
    {
        $this->assertTrue($this->policy->manageUsers($this->admin));
    }

    public function testNonAdminCannotManageUsers()
    {
        $this->assertFalse($this->policy->manageUsers($this->nonAdmin));
    }

    public function testAdminCanManageRoles()
    {
        $this->assertTrue($this->policy->manageRoles($this->admin));
    }

    public function testNonAdminCannotManageRoles()
    {
        $this->assertFalse($this->policy->manageRoles($this->nonAdmin));
    }

    public function testAdminCanManagePermissions()
    {
        $this->assertTrue($this->policy->managePermissions($this->admin));
    }

    public function testNonAdminCannotManagePermissions()
    {
        $this->assertFalse($this->policy->managePermissions($this->nonAdmin));
    }

    public function testAdminCanManageSettings()
    {
        $this->assertTrue($this->policy->manageSettings($this->admin));
    }

    public function testNonAdminCannotManageSettings()
    {
        $this->assertFalse($this->policy->manageSettings($this->nonAdmin));
    }

    public function testAdminCanHandleWebhooks()
    {
        $this->assertTrue($this->policy->handleWebhooks($this->admin));
    }

    public function testNonAdminCannotHandleWebhooks()
    {
        $this->assertFalse($this->policy->handleWebhooks($this->nonAdmin));
    }

    public function testAdminCanManageReports()
    {
        $this->assertTrue($this->policy->manageReports($this->admin));
    }

    public function testNonAdminCannotManageReports()
    {
        $this->assertFalse($this->policy->manageReports($this->nonAdmin));
    }

    public function testAdminCanManageShipping()
    {
        $this->assertTrue($this->policy->manageShipping($this->admin));
    }

    public function testNonAdminCannotManageShipping()
    {
        $this->assertFalse($this->policy->manageShipping($this->nonAdmin));
    }

    public function testAdminCanManageProducts()
    {
        $this->assertTrue($this->policy->manageProducts($this->admin));
    }

    public function testNonAdminCannotManageProducts()
    {
        $this->assertFalse($this->policy->manageProducts($this->nonAdmin));
    }

    public function testAdminCanManageInventory()
    {
        $this->assertTrue($this->policy->manageInventory($this->admin));
    }

    public function testNonAdminCannotManageInventory()
    {
        $this->assertFalse($this->policy->manageInventory($this->nonAdmin));
    }

    public function testAdminCanManagePayments()
    {
        $this->assertTrue($this->policy->managePayments($this->admin));
    }

    public function testNonAdminCannotManagePayments()
    {
        $this->assertFalse($this->policy->managePayments($this->nonAdmin));
    }

    public function testAdminCanViewSensitiveData()
    {
        $this->assertTrue($this->policy->viewSensitiveData($this->admin));
    }

    public function testNonAdminCannotViewSensitiveData()
    {
        $this->assertFalse($this->policy->viewSensitiveData($this->nonAdmin));
    }

    public function testAdminCanUploadFiles()
    {
        $this->assertTrue($this->policy->uploadFiles($this->admin));
    }

    public function testNonAdminCannotUploadFiles()
    {
        $this->assertFalse($this->policy->uploadFiles($this->nonAdmin));
    }

    public function testAdminCanDeleteFiles()
    {
        $this->assertTrue($this->policy->deleteFiles($this->admin));
    }

    public function testNonAdminCannotDeleteFiles()
    {
        $this->assertFalse($this->policy->deleteFiles($this->nonAdmin));
    }
}
