<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\InventoryMovement;
use App\Models\Inventory;
use App\Models\User;

class InventoryMovementControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_inventory_movements()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        InventoryMovement::factory()->count(3)->create();

        $response = $this->getJson('/api/inventory_movements');

        $response->assertStatus(200)
            ->assertJsonStructure([[]]); // Expect an array of inventory movements
    }

    public function test_user_can_create_inventory_movement()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $inventory = Inventory::factory()->create();

        $response = $this->postJson('/api/inventory_movements', [
            'inventory_id' => $inventory->id,
            'quantity' => 10,
            'movement_type' => 'Restock',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'inventory_id', 'quantity', 'movement_type']);
    }

    public function test_user_can_view_single_inventory_movement()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $inventoryMovement = InventoryMovement::factory()->create();

        $response = $this->getJson("/api/inventory_movements/{$inventoryMovement->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'inventory_id', 'quantity', 'movement_type']);
    }

    public function test_user_can_update_inventory_movement()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $inventoryMovement = InventoryMovement::factory()->create();

        $response = $this->putJson("/api/inventory_movements/{$inventoryMovement->id}", [
            'quantity' => 20,
            'movement_type' => 'Sale',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'inventory_id', 'quantity', 'movement_type']);
    }

    public function test_user_can_delete_inventory_movement()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $inventoryMovement = InventoryMovement::factory()->create();

        $response = $this->deleteJson("/api/inventory_movements/{$inventoryMovement->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('inventory_movements', ['id' => $inventoryMovement->id]);
    }
}
