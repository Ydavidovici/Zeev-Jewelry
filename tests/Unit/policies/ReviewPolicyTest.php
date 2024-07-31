<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\Review;
use App\Policies\ReviewPolicy;
use PHPUnit\Framework\TestCase;

class ReviewPolicyTest extends TestCase
{
    protected $adminUser;
    protected $sellerUser;
    protected $customerUser;
    protected $review;
    protected $reviewPolicy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->make(['role' => 'admin-page']);
        $this->sellerUser = User::factory()->make(['role' => 'seller-page']);
        $this->customerUser = User::factory()->make(['role' => 'customer']);
        $this->review = Review::factory()->make();

        $this->reviewPolicy = new ReviewPolicy();
    }

    public function test_view_any()
    {
        $this->assertTrue($this->reviewPolicy->viewAny($this->adminUser));
        $this->assertTrue($this->reviewPolicy->viewAny($this->sellerUser));
        $this->assertTrue($this->reviewPolicy->viewAny($this->customerUser));
    }

    public function test_view()
    {
        $this->assertTrue($this->reviewPolicy->view($this->adminUser, $this->review));
        $this->assertTrue($this->reviewPolicy->view($this->sellerUser, $this->review));
        $this->assertTrue($this->reviewPolicy->view($this->customerUser, $this->review));
    }

    public function test_create()
    {
        $this->assertTrue($this->reviewPolicy->create($this->adminUser));
        $this->assertFalse($this->reviewPolicy->create($this->sellerUser));
        $this->assertTrue($this->reviewPolicy->create($this->customerUser));
    }

    public function test_update()
    {
        $this->assertTrue($this->reviewPolicy->update($this->adminUser, $this->review));
        $this->assertFalse($this->reviewPolicy->update($this->sellerUser, $this->review));
        $this->assertFalse($this->reviewPolicy->update($this->customerUser, $this->review));
    }

    public function test_delete()
    {
        $this->assertTrue($this->reviewPolicy->delete($this->adminUser, $this->review));
        $this->assertFalse($this->reviewPolicy->delete($this->sellerUser, $this->review));
        $this->assertFalse($this->reviewPolicy->delete($this->customerUser, $this->review));
    }
}
