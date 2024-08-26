<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\InventoryMovement;
use App\Models\Inventory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class InventoryMovementTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function inventory_movement_belongs_to_inventory()
    {
        $inventory = Inventory::factory()->create();
        $inventoryMovement = InventoryMovement::factory()->create(['inventory_id' => $inventory->id]);

        $this->assertInstanceOf(Inventory::class, $inventoryMovement->inventory);
        $this->assertEquals($inventory->id, $inventoryMovement->inventory->id);
    }

    #[Test]
    public function inventory_movement_has_quantity()
    {
        $inventoryMovement = InventoryMovement::factory()->create(['quantity_change' => 50]);

        $this->assertEquals(50, $inventoryMovement->quantity_change);
    }
}
