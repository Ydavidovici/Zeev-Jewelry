<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\InventoryMovement;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InventoryMovementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_an_inventory_movement()
    {
        $inventoryMovement = InventoryMovement::factory()->create([
            'inventory_id' => 1,
            'type' => 'addition',
            'quantity_change' => 10,
        ]);

        $this->assertDatabaseHas('inventory_movements', ['type' => 'addition']);
    }
}
