<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\User;

class InventoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_inventory()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        Inventory::factory()->count(3)->create();

        $response = $this->getJson('/api/inventories');

        $response->assertStatus(200)
            ->assertJsonStructure([[]]); // Expect an array of inventories
    }

    public function test_user_can_create_inventory()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $product = Product::factory()->create();

        $response = $this->postJson('/api/inventories', [
            'product_id' => $product->id,
            'quantity' => 10,
            'location' => 'Warehouse 1',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'product_id', 'quantity', 'location']);
    }

    public function test_user_can_view_single_inventory()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $inventory = Inventory::factory()->create();

        $response = $this->getJson("/api/inventories/{$inventory->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'product_id', 'quantity', 'location']);
    }

    public function test_user_can_update_inventory()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $inventory = Inventory::factory()->create();

        $response = $this->putJson("/api/inventories/{$inventory->id}", [
            'quantity' => 20,
            'location' => 'Warehouse 2',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'product_id', 'quantity', 'location']);
    }

    public function test_user_can_delete_inventory()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $inventory = Inventory::factory()->create();

        $response = $this->deleteJson("/api/inventories/{$inventory->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('inventories', ['id' => $inventory->id]);
    }
}
