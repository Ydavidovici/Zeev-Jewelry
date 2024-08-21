<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\Product;
use App\Policies\ProductPolicy;
use PHPUnit\Framework\TestCase;

class ProductPolicyTest extends TestCase
{
    protected $adminUser;
    protected $sellerUser;
    protected $customerUser;
    protected $product;
    protected $productPolicy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->make(['role' => 'admin-page']);
        $this->sellerUser = User::factory()->make(['role' => 'seller-page']);
        $this->customerUser = User::factory()->make(['role' => 'customer']);
        $this->product = Product::factory()->make();

        $this->productPolicy = new ProductPolicy();
    }

    public function test_view_any()
    {
        $this->assertTrue($this->productPolicy->viewAny($this->adminUser));
        $this->assertTrue($this->productPolicy->viewAny($this->sellerUser));
        $this->assertTrue($this->productPolicy->viewAny($this->customerUser));
    }

    public function test_view()
    {
        $this->assertTrue($this->productPolicy->view($this->adminUser, $this->product));
        $this->assertTrue($this->productPolicy->view($this->sellerUser, $this->product));
        $this->assertTrue($this->productPolicy->view($this->customerUser, $this->product));
    }

    public function test_create()
    {
        $this->assertTrue($this->productPolicy->create($this->adminUser));
        $this->assertTrue($this->productPolicy->create($this->sellerUser));
        $this->assertFalse($this->productPolicy->create($this->customerUser));
    }

    public function test_update()
    {
        $this->assertTrue($this->productPolicy->update($this->adminUser, $this->product));
        $this->assertTrue($this->productPolicy->update($this->sellerUser, $this->product));
        $this->assertFalse($this->productPolicy->update($this->customerUser, $this->product));
    }

    public function test_delete()
    {
        $this->assertTrue($this->productPolicy->delete($this->adminUser, $this->product));
        $this->assertFalse($this->productPolicy->delete($this->sellerUser, $this->product));
        $this->assertFalse($this->productPolicy->delete($this->customerUser, $this->product));
    }
}
