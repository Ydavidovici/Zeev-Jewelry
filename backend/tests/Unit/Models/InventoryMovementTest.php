<?php

namespace Tests\Unit\Models;

use App\Models\Inventory;
use App\Models\InventoryMovement;
use backend\tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InventoryMovementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_an_inventory_movement()
    {
        $inventory = Inventory::factory()->create(); // Create an inventory first
        $inventoryMovement = InventoryMovement::factory()->create([
            'inventory_id' => $inventory->id,
            'type' => 'addition',
            'quantity_change' => 10,
        ]);

        $this->assertDatabaseHas('inventory_movements', ['type' => 'addition']);
    }
}
