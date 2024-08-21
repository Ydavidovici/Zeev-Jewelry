<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\Inventory;
use App\Policies\InventoryPolicy;
use PHPUnit\Framework\TestCase;

class InventoryPolicyTest extends TestCase
{
    protected $adminUser;
    protected $sellerUser;
    protected $customerUser;
    protected $inventory;
    protected $inventoryPolicy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->make(['role' => 'admin-page']);
        $this->sellerUser = User::factory()->make(['role' => 'seller-page']);
        $this->customerUser = User::factory()->make(['role' => 'customer']);
        $this->inventory = Inventory::factory()->make();

        $this->inventoryPolicy = new InventoryPolicy();
    }

    public function test_view_any()
    {
        $this->assertTrue($this->inventoryPolicy->viewAny($this->adminUser));
        $this->assertTrue($this->inventoryPolicy->viewAny($this->sellerUser));
        $this->assertFalse($this->inventoryPolicy->viewAny($this->customerUser));
    }

    public function test_view()
    {
        $this->assertTrue($this->inventoryPolicy->view($this->adminUser, $this->inventory));
        $this->assertTrue($this->inventoryPolicy->view($this->sellerUser, $this->inventory));
        $this->assertFalse($this->inventoryPolicy->view($this->customerUser, $this->inventory));
    }

    public function test_create()
    {
        $this->assertTrue($this->inventoryPolicy->create($this->adminUser));
        $this->assertTrue($this->inventoryPolicy->create($this->sellerUser));
        $this->assertFalse($this->inventoryPolicy->create($this->customerUser));
    }

    public function test_update()
    {
        $this->assertTrue($this->inventoryPolicy->update($this->adminUser, $this->inventory));
        $this->assertTrue($this->inventoryPolicy->update($this->sellerUser, $this->inventory));
        $this->assertFalse($this->inventoryPolicy->update($this->customerUser, $this->inventory));
    }

    public function test_delete()
    {
        $this->assertTrue($this->inventoryPolicy->delete($this->adminUser, $this->inventory));
        $this->assertFalse($this->inventoryPolicy->delete($this->sellerUser, $this->inventory));
        $this->assertFalse($this->inventoryPolicy->delete($this->customerUser, $this->inventory));
    }
}
