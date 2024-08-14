<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Policies\ReportPolicy;
use PHPUnit\Framework\TestCase;

class ReportPolicyTest extends TestCase
{
    protected $adminUser;
    protected $sellerUser;
    protected $customerUser;
    protected $reportPolicy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->make(['role' => 'admin']);
        $this->sellerUser = User::factory()->make(['role' => 'seller']);
        $this->customerUser = User::factory()->make(['role' => 'customer']);

        $this->reportPolicy = new ReportPolicy();
    }

    public function test_view_any()
    {
        $this->assertTrue($this->reportPolicy->viewAny($this->adminUser));
        $this->assertTrue($this->reportPolicy->viewAny($this->sellerUser));
        $this->assertFalse($this->reportPolicy->viewAny($this->customerUser));
    }

    public function test_view()
    {
        $this->assertTrue($this->reportPolicy->view($this->adminUser));
        $this->assertTrue($this->reportPolicy->view($this->sellerUser));
        $this->assertFalse($this->reportPolicy->view($this->customerUser));
    }

    public function test_create()
    {
        $this->assertTrue($this->reportPolicy->create($this->adminUser));
        $this->assertTrue($this->reportPolicy->create($this->sellerUser));
        $this->assertFalse($this->reportPolicy->create($this->customerUser));
    }
}
