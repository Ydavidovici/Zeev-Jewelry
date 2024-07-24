<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Inventory;
use App\Models\User;
use App\Models\Product;

class InventoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and set them as the current authenticated user
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->seller = User::factory()->create(['role' => 'seller']);
        $this->actingAs($this->admin);
    }

    /** @test */
    public function admin_can_view_inventories_index()
    {
        $response = $this->get(route('inventories.index'));

        $response->assertStatus(200);
        $response->assertViewIs('inventories.index');
        $response->assertViewHas('inventories');
    }

    /** @test */
    public function seller_can_view_own_inventories_index()
    {
        $this->actingAs($this->seller);

        Inventory::factory()->create(['user_id' => $this->seller->id]);

        $response = $this->get(route('inventories.index'));

        $response->assertStatus(200);
        $response->assertViewIs('inventories.index');
        $response->assertViewHas('inventories');
    }

    /** @test */
    public function admin_can_view_create_inventory_form()
    {
        $response = $this->get(route('inventories.create'));

        $response->assertStatus(200);
        $response->assertViewIs('inventories.create');
    }

    /** @test */
    public function admin_can_create_inventory()
    {
        $product = Product::factory()->create();

        $data = [
            'product_id' => $product->id,
            'quantity' => 10,
            'location' => 'Warehouse A',
        ];

        $response = $this->post(route('inventories.store'), $data);

        $response->assertRedirect(route('inventories.index'));
        $response->assertSessionHas('success', 'Inventory added successfully');
        $this->assertDatabaseHas('inventories', $data);
    }

    /** @test */
    public function admin_can_view_edit_inventory_form()
    {
        $inventory = Inventory::factory()->create();

        $response = $this->get(route('inventories.edit', $inventory));

        $response->assertStatus(200);
        $response->assertViewIs('inventories.edit');
        $response->assertViewHas('inventory', $inventory);
    }

    /** @test */
    public function admin_can_update_inventory()
    {
        $inventory = Inventory::factory()->create();

        $data = [
            'product_id' => $inventory->product_id,
            'quantity' => 20,
            'location' => 'Updated Location',
        ];

        $response = $this->put(route('inventories.update', $inventory), $data);

        $response->assertRedirect(route('inventories.index'));
        $response->assertSessionHas('success', 'Inventory updated successfully');
        $this->assertDatabaseHas('inventories', array_merge(['id' => $inventory->id], $data));
    }

    /** @test */
    public function admin_can_delete_inventory()
    {
        $inventory = Inventory::factory()->create();

        $response = $this->delete(route('inventories.destroy', $inventory));

        $response->assertRedirect(route('inventories.index'));
        $response->assertSessionHas('success', 'Inventory deleted successfully');
        $this->assertDatabaseMissing('inventories', ['id' => $inventory->id]);
    }
}
