<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\InventoryMovement;

class InventoryMovementTest extends TestCase
{
    public function test_inventory_movement_has_inventory_id()
    {
        $inventoryMovement = new InventoryMovement(['inventory_id' => 1]);

        $this->assertEquals(1, $inventoryMovement->inventory_id);
    }

    public function test_inventory_movement_has_quantity()
    {
        $inventoryMovement = new InventoryMovement(['quantity' => 50]);

        $this->assertEquals(50, $inventoryMovement->quantity);
    }

    public function test_inventory_movement_has_movement_type()
    {
        $inventoryMovement = new InventoryMovement(['movement_type' => 'in']);

        $this->assertEquals('in', $inventoryMovement->movement_type);
    }

    public function test_inventory_movement_belongs_to_inventory()
    {
        $inventoryMovement = new InventoryMovement();
        $relation = $inventoryMovement->inventory();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $relation);
        $this->assertEquals('inventory_id', $relation->getForeignKeyName());
    }
}
