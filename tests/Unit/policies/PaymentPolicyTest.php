<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\Payment;
use App\Policies\PaymentPolicy;
use PHPUnit\Framework\TestCase;

class PaymentPolicyTest extends TestCase
{
    protected $adminUser;
    protected $sellerUser;
    protected $customerUser;
    protected $payment;
    protected $paymentPolicy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->make(['role' => 'admin-page']);
        $this->sellerUser = User::factory()->make(['role' => 'seller-page']);
        $this->customerUser = User::factory()->make(['role' => 'customer']);
        $this->payment = Payment::factory()->make();

        $this->paymentPolicy = new PaymentPolicy();
    }

    public function test_view_any()
    {
        $this->assertTrue($this->paymentPolicy->viewAny($this->adminUser));
        $this->assertTrue($this->paymentPolicy->viewAny($this->sellerUser));
        $this->assertTrue($this->paymentPolicy->viewAny($this->customerUser));
    }

    public function test_view()
    {
        $this->assertTrue($this->paymentPolicy->view($this->adminUser, $this->payment));
        $this->assertTrue($this->paymentPolicy->view($this->sellerUser, $this->payment));
        $this->assertTrue($this->paymentPolicy->view($this->customerUser, $this->payment));
    }

    public function test_create()
    {
        $this->assertTrue($this->paymentPolicy->create($this->adminUser));
        $this->assertTrue($this->paymentPolicy->create($this->sellerUser));
        $this->assertTrue($this->paymentPolicy->create($this->customerUser));
    }

    public function test_update()
    {
        $this->assertTrue($this->paymentPolicy->update($this->adminUser, $this->payment));
        $this->assertTrue($this->paymentPolicy->update($this->sellerUser, $this->payment));
        $this->assertFalse($this->paymentPolicy->update($this->customerUser, $this->payment));
    }

    public function test_delete()
    {
        $this->assertTrue($this->paymentPolicy->delete($this->adminUser, $this->payment));
        $this->assertFalse($this->paymentPolicy->delete($this->sellerUser, $this->payment));
        $this->assertFalse($this->paymentPolicy->delete($this->customerUser, $this->payment));
    }
}
