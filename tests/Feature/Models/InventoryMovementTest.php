<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\InventoryMovement;
use App\Models\Inventory;

class InventoryMovementTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_inventory_movement()
    {
        $inventory = Inventory::factory()->create();
        $data = [
            'inventory_id' => $inventory->id,
            'quantity' => 50,
            'movement_type' => 'in',
        ];

        $response = $this->post(route('inventory_movements.store'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('inventory_movements', ['inventory_id' => $inventory->id, 'quantity' => 50]);
    }

    public function test_read_inventory_movement()
    {
        $inventoryMovement = InventoryMovement::factory()->create();

        $response = $this->get(route('inventory_movements.show', $inventoryMovement->id));

        $response->assertStatus(200);
        $response->assertJson($inventoryMovement->toArray());
    }

    public function test_update_inventory_movement()
    {
        $inventoryMovement = InventoryMovement::factory()->create();
        $data = [
            'quantity' => 100,
            'movement_type' => 'out',
        ];

        $response = $this->put(route('inventory_movements.update', $inventoryMovement->id), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('inventory_movements', ['id' => $inventoryMovement->id, 'quantity' => 100, 'movement_type' => 'out']);
    }

    public function test_delete_inventory_movement()
    {
        $inventoryMovement = InventoryMovement::factory()->create();
        $inventoryMovementId = $inventoryMovement->id;

        $response = $this->delete(route('inventory_movements.destroy', $inventoryMovementId));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('inventory_movements', ['id' => $inventoryMovementId]);
    }
}
