<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\InventoryMovement;
use App\Policies\InventoryMovementPolicy;
use PHPUnit\Framework\TestCase;

class InventoryMovementPolicyTest extends TestCase
{
    protected $adminUser;
    protected $sellerUser;
    protected $customerUser;
    protected $inventoryMovement;
    protected $inventoryMovementPolicy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->make(['role' => 'admin-page']);
        $this->sellerUser = User::factory()->make(['role' => 'seller-page']);
        $this->customerUser = User::factory()->make(['role' => 'customer']);
        $this->inventoryMovement = InventoryMovement::factory()->make();

        $this->inventoryMovementPolicy = new InventoryMovementPolicy();
    }

    public function test_view_any()
    {
        $this->assertTrue($this->inventoryMovementPolicy->viewAny($this->adminUser));
        $this->assertTrue($this->inventoryMovementPolicy->viewAny($this->sellerUser));
        $this->assertFalse($this->inventoryMovementPolicy->viewAny($this->customerUser));
    }

    public function test_view()
    {
        $this->assertTrue($this->inventoryMovementPolicy->view($this->adminUser, $this->inventoryMovement));
        $this->assertTrue($this->inventoryMovementPolicy->view($this->sellerUser, $this->inventoryMovement));
        $this->assertFalse($this->inventoryMovementPolicy->view($this->customerUser, $this->inventoryMovement));
    }

    public function test_create()
    {
        $this->assertTrue($this->inventoryMovementPolicy->create($this->adminUser));
        $this->assertTrue($this->inventoryMovementPolicy->create($this->sellerUser));
        $this->assertFalse($this->inventoryMovementPolicy->create($this->customerUser));
    }

    public function test_update()
    {
        $this->assertTrue($this->inventoryMovementPolicy->update($this->adminUser, $this->inventoryMovement));
        $this->assertTrue($this->inventoryMovementPolicy->update($this->sellerUser, $this->inventoryMovement));
        $this->assertFalse($this->inventoryMovementPolicy->update($this->customerUser, $this->inventoryMovement));
    }

    public function test_delete()
    {
        $this->assertTrue($this->inventoryMovementPolicy->delete($this->adminUser, $this->inventoryMovement));
        $this->assertFalse($this->inventoryMovementPolicy->delete($this->sellerUser, $this->inventoryMovement));
        $this->assertFalse($this->inventoryMovementPolicy->delete($this->customerUser, $this->inventoryMovement));
    }
}
