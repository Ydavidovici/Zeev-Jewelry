<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use App\Models\InventoryMovement;
use App\Models\User;
use App\Models\Inventory;

class InventoryMovementControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and set them as the current authenticated user
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($this->admin);
    }

    /** @test */
    public function admin_can_view_inventory_movements_index()
    {
        $response = $this->get(route('inventory-movements.index'));

        $response->assertStatus(200);
        $response->assertViewIs('inventory_movements.index');
        $response->assertViewHas('inventoryMovements');
        Log::shouldReceive('info')->once()->with('User viewed inventory movements.', ['user_id' => $this->admin->id]);
    }

    /** @test */
    public function admin_can_view_create_inventory_movement_form()
    {
        $response = $this->get(route('inventory-movements.create'));

        $response->assertStatus(200);
        $response->assertViewIs('inventory_movements.create');
    }

    /** @test */
    public function admin_can_create_inventory_movement()
    {
        $inventory = Inventory::factory()->create();

        $data = [
            'inventory_id' => $inventory->id,
            'quantity' => 10,
            'movement_type' => 'addition',
        ];

        $response = $this->post(route('inventory-movements.store'), $data);

        $response->assertRedirect(route('inventory-movements.index'));
        $response->assertSessionHas('success', 'Inventory movement created successfully.');
        $this->assertDatabaseHas('inventory_movements', $data);
        Log::shouldReceive('info')->once()->with('Inventory movement created.', ['user_id' => $this->admin->id, 'data' => $data]);
    }

    /** @test */
    public function admin_can_view_edit_inventory_movement_form()
    {
        $inventoryMovement = InventoryMovement::factory()->create();

        $response = $this->get(route('inventory-movements.edit', $inventoryMovement));

        $response->assertStatus(200);
        $response->assertViewIs('inventory_movements.edit');
        $response->assertViewHas('inventoryMovement', $inventoryMovement);
    }

    /** @test */
    public function admin_can_update_inventory_movement()
    {
        $inventoryMovement = InventoryMovement::factory()->create();

        $data = [
            'inventory_id' => $inventoryMovement->inventory_id,
            'quantity' => 20,
            'movement_type' => 'subtraction',
        ];

        $response = $this->put(route('inventory-movements.update', $inventoryMovement), $data);

        $response->assertRedirect(route('inventory-movements.index'));
        $response->assertSessionHas('success', 'Inventory movement updated successfully.');
        $this->assertDatabaseHas('inventory_movements', array_merge(['id' => $inventoryMovement->id], $data));
        Log::shouldReceive('info')->once()->with('Inventory movement updated.', ['user_id' => $this->admin->id, 'inventory_movement_id' => $inventoryMovement->id, 'data' => $data]);
    }

    /** @test */
    public function admin_can_delete_inventory_movement()
    {
        $inventoryMovement = InventoryMovement::factory()->create();

        $response = $this->delete(route('inventory-movements.destroy', $inventoryMovement));

        $response->assertRedirect(route('inventory-movements.index'));
        $response->assertSessionHas('success', 'Inventory movement deleted successfully.');
        $this->assertDatabaseMissing('inventory_movements', ['id' => $inventoryMovement->id]);
        Log::shouldReceive('info')->once()->with('Inventory movement deleted.', ['user_id' => $this->admin->id, 'inventory_movement_id' => $inventoryMovement->id]);
    }
}
