<?php

namespace Tests\Feature\Controllers;

use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InventoryMovementControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    #[Test]
    public function it_can_view_all_inventory_movements()
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');
        $this->actingAs($seller, 'api');

        // Create necessary data
        $product = Product::factory()->create(['seller_id' => $seller->id]);
        $inventory = Inventory::factory()->create(['product_id' => $product->id]);
        $inventoryMovement = InventoryMovement::factory()->create(['inventory_id' => $inventory->id]);

        $response = $this->getJson(route('inventory-movements.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['*' => ['id', 'inventory_id', 'quantity_change', 'movement_type', 'movement_date']]);

        // Ensure that only the seller's inventory movements are returned
        $this->assertCount(1, $response->json());
    }

    #[Test]
    public function it_can_create_an_inventory_movement()
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');
        $this->actingAs($seller, 'api');

        // Create a product and inventory
        $product = Product::factory()->create(['seller_id' => $seller->id]);
        $inventory = Inventory::factory()->create(['product_id' => $product->id]);

        $inventoryMovementData = [
            'inventory_id' => $inventory->id,
            'quantity_change' => 10,
            'movement_type' => 'addition',
            'movement_date' => now()->format('Y-m-d H:i:s'),
        ];

        $response = $this->postJson(route('inventory-movements.store'), $inventoryMovementData);

        $response->assertStatus(201)
            ->assertJsonFragment(['movement_type' => 'addition']);

        $this->assertDatabaseHas('inventory_movements', [
            'quantity_change' => 10,
            'movement_type' => 'addition',
            'inventory_id' => $inventory->id,
        ]);
    }

    #[Test]
    public function it_can_show_an_inventory_movement()
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');
        $this->actingAs($seller, 'api');

        // Create necessary data
        $product = Product::factory()->create([
            'seller_id' => $seller->id,
        ]);
        $inventory = Inventory::factory()->create([
            'product_id' => $product->id,
        ]);
        $inventoryMovement = InventoryMovement::factory()->create([
            'inventory_id' => $inventory->id,
        ]);

        $response = $this->getJson(route('inventory-movements.show', $inventoryMovement->id));

        $response->assertStatus(200)
            ->assertJson(['id' => $inventoryMovement->id]);
    }

    #[Test]
    public function it_can_update_an_inventory_movement()
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');
        $this->actingAs($seller, 'api');

        // Create necessary data
        $product = Product::factory()->create(['seller_id' => $seller->id]);
        $inventory = Inventory::factory()->create(['product_id' => $product->id]);
        $inventoryMovement = InventoryMovement::factory()->create(['inventory_id' => $inventory->id]);

        $response = $this->putJson(route('inventory-movements.update', $inventoryMovement->id), [
            'inventory_id' => $inventory->id,
            'quantity_change' => 20,
            'movement_type' => 'subtraction',
            'movement_date' => now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['quantity_change' => 20]);

        $this->assertDatabaseHas('inventory_movements', ['id' => $inventoryMovement->id, 'quantity_change' => 20]);
    }

    #[Test]
    public function it_can_delete_an_inventory_movement()
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');
        $this->actingAs($seller, 'api');

        // Create necessary data
        $product = Product::factory()->create(['seller_id' => $seller->id]);
        $inventory = Inventory::factory()->create(['product_id' => $product->id]);
        $inventoryMovement = InventoryMovement::factory()->create(['inventory_id' => $inventory->id]);

        $response = $this->deleteJson(route('inventory-movements.destroy', $inventoryMovement->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('inventory_movements', ['id' => $inventoryMovement->id]);
    }

    #[Test]
    public function non_seller_cannot_access_inventory_movement()
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');

        $otherSeller = User::factory()->create();
        $otherSeller->assignRole('seller');
        $this->actingAs($otherSeller, 'api');

        // Create necessary data
        $product = Product::factory()->create(['seller_id' => $seller->id]);
        $inventory = Inventory::factory()->create(['product_id' => $product->id]);
        $inventoryMovement = InventoryMovement::factory()->create(['inventory_id' => $inventory->id]);

        $response = $this->getJson(route('inventory-movements.show', $inventoryMovement->id));

        $response->assertStatus(403);
    }
}
