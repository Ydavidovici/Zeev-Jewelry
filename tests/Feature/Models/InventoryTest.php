<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Inventory;
use App\Models\Product;

class InventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_inventory()
    {
        $product = Product::factory()->create();
        $data = [
            'product_id' => $product->id,
            'quantity' => 100,
            'location' => 'Warehouse A',
        ];

        $response = $this->post(route('inventory.store'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('inventory', ['product_id' => $product->id, 'location' => 'Warehouse A']);
    }

    public function test_read_inventory()
    {
        $inventory = Inventory::factory()->create();

        $response = $this->get(route('inventory.show', $inventory->id));

        $response->assertStatus(200);
        $response->assertJson($inventory->toArray());
    }

    public function test_update_inventory()
    {
        $inventory = Inventory::factory()->create();
        $data = [
            'quantity' => 200,
            'location' => 'Warehouse B',
        ];

        $response = $this->put(route('inventory.update', $inventory->id), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('inventory', ['id' => $inventory->id, 'quantity' => 200, 'location' => 'Warehouse B']);
    }

    public function test_delete_inventory()
    {
        $inventory = Inventory::factory()->create();
        $inventoryId = $inventory->id;

        $response = $this->delete(route('inventory.destroy', $inventoryId));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('inventory', ['id' => $inventoryId]);
    }
}
