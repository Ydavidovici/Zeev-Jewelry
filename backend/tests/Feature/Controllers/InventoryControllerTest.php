<?php

namespace Tests\Feature\Controllers;

use App\Models\Inventory;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_view_all_inventories_as_seller()
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller'); // Assign the seller role
        $this->actingAs($seller, 'api');

        $inventory = Inventory::factory()->create(['seller_id' => $seller->id]);

        $response = $this->getJson(route('inventory.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['*' => ['id', 'product_id', 'quantity', 'location']]);
    }

    /** @test */
    public function it_can_create_inventory_as_seller()
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller'); // Assign the seller role
        $this->actingAs($seller, 'api');

        // Ensure a valid product exists before creating inventory
        $product = Product::factory()->create();

        $inventoryData = [
            'product_id' => $product->id,
            'quantity' => 10,
            'location' => 'Warehouse A'
        ];

        $response = $this->postJson(route('inventory.store'), $inventoryData);

        $response->assertStatus(201)
            ->assertJsonFragment(['location' => 'Warehouse A']);

        $this->assertDatabaseHas('inventory', ['product_id' => $product->id, 'location' => 'Warehouse A']);
    }

    /** @test */
    public function it_can_show_inventory_as_seller()
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller'); // Assign the seller role
        $this->actingAs($seller, 'api');

        $inventory = Inventory::factory()->create(['seller_id' => $seller->id]);

        $response = $this->getJson(route('inventory.show', $inventory->id));

        $response->assertStatus(200)
            ->assertJson(['id' => $inventory->id]);
    }

    /** @test */
    public function it_can_update_inventory_as_seller()
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller'); // Assign the seller role
        $this->actingAs($seller, 'api');

        // Ensure a valid product exists
        $product = Product::factory()->create();

        $inventory = Inventory::factory()->create([
            'seller_id' => $seller->id,
            'product_id' => $product->id,
            'location' => 'Warehouse A'
        ]);

        // Include product_id and location, which are required
        $response = $this->putJson(route('inventory.update', $inventory->id), [
            'product_id' => $product->id,
            'quantity' => 15,
            'location' => 'Warehouse A'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['quantity' => 15]);

        $this->assertDatabaseHas('inventory', ['id' => $inventory->id, 'quantity' => 15]);
    }

    /** @test */
    public function it_can_delete_inventory_as_seller()
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller'); // Assign the seller role
        $this->actingAs($seller, 'api');

        $inventory = Inventory::factory()->create(['seller_id' => $seller->id]);

        $response = $this->deleteJson(route('inventory.destroy', $inventory->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('inventory', ['id' => $inventory->id]);
    }

    /** @test */
    public function non_sellers_or_non_admins_cannot_view_inventories()
    {
        $user = User::factory()->create();  // Not a seller or admin
        $this->actingAs($user, 'api');

        $response = $this->getJson(route('inventory.index'));

        $response->assertStatus(403); // Forbidden
    }
}
