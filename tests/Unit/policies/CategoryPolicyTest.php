<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\Category;
use App\Policies\CategoryPolicy;
use PHPUnit\Framework\TestCase;

class CategoryPolicyTest extends TestCase
{
    protected $adminUser;
    protected $sellerUser;
    protected $customerUser;
    protected $category;
    protected $categoryPolicy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->make(['role' => 'admin-page']);
        $this->sellerUser = User::factory()->make(['role' => 'seller-page']);
        $this->customerUser = User::factory()->make(['role' => 'customer']);
        $this->category = Category::factory()->make();

        $this->categoryPolicy = new CategoryPolicy();
    }

    public function test_view_any()
    {
        $this->assertTrue($this->categoryPolicy->viewAny($this->adminUser));
        $this->assertTrue($this->categoryPolicy->viewAny($this->sellerUser));
        $this->assertTrue($this->categoryPolicy->viewAny($this->customerUser));
    }

    public function test_view()
    {
        $this->assertTrue($this->categoryPolicy->view($this->adminUser, $this->category));
        $this->assertTrue($this->categoryPolicy->view($this->sellerUser, $this->category));
        $this->assertTrue($this->categoryPolicy->view($this->customerUser, $this->category));
    }

    public function test_create()
    {
        $this->assertTrue($this->categoryPolicy->create($this->adminUser));
        $this->assertTrue($this->categoryPolicy->create($this->sellerUser));
        $this->assertFalse($this->categoryPolicy->create($this->customerUser));
    }

    public function test_update()
    {
        $this->assertTrue($this->categoryPolicy->update($this->adminUser, $this->category));
        $this->assertTrue($this->categoryPolicy->update($this->sellerUser, $this->category));
        $this->assertFalse($this->categoryPolicy->update($this->customerUser, $this->category));
    }

    public function test_delete()
    {
        $this->assertTrue($this->categoryPolicy->delete($this->adminUser, $this->category));
        $this->assertFalse($this->categoryPolicy->delete($this->sellerUser, $this->category));
        $this->assertFalse($this->categoryPolicy->delete($this->customerUser, $this->category));
    }
}
