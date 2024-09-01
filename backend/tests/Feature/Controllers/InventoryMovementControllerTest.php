<?php

namespace Tests\Feature\Controllers;

use App\Models\InventoryMovement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class InventoryMovementControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create(), 'api');
    }

    /** @test */
    public function it_can_view_all_inventory_movements()
    {
        Gate::define('viewAny', function ($user) {
            return true;
        });

        $response = $this->getJson(route('inventory-movements.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['*' => ['id', 'inventory_id', 'quantity', 'movement_type']]);
    }

    /** @test */
    public function it_can_create_an_inventory_movement()
    {
        Gate::define('create', function ($user) {
            return true;
        });

        $inventoryMovementData = [
            'inventory_id' => 1,
            'quantity' => 10,
            'movement_type' => 'addition',
        ];

        $response = $this->postJson(route('inventory-movements.store'), $inventoryMovementData);

        $response->assertStatus(201)
            ->assertJsonFragment(['movement_type' => 'addition']);

        $this->assertDatabaseHas('inventory_movements', ['quantity' => 10, 'movement_type' => 'addition']);
    }

    /** @test */
    public function it_can_show_an_inventory_movement()
    {
        Gate::define('view', function ($user, $inventoryMovement) {
            return true;
        });

        $inventoryMovement = InventoryMovement::factory()->create();

        $response = $this->getJson(route('inventory-movements.show', $inventoryMovement->id));

        $response->assertStatus(200)
            ->assertJson(['id' => $inventoryMovement->id]);
    }

    /** @test */
    public function it_can_update_an_inventory_movement()
    {
        Gate::define('update', function ($user, $inventoryMovement) {
            return true;
        });

        $inventoryMovement = InventoryMovement::factory()->create();

        $response = $this->putJson(route('inventory-movements.update', $inventoryMovement->id), ['quantity' => 20]);

        $response->assertStatus(200)
            ->assertJsonFragment(['quantity' => 20]);

        $this->assertDatabaseHas('inventory_movements', ['id' => $inventoryMovement->id, 'quantity' => 20]);
    }

    /** @test */
    public function it_can_delete_an_inventory_movement()
    {
        Gate::define('delete', function ($user, $inventoryMovement) {
            return true;
        });

        $inventoryMovement = InventoryMovement::factory()->create();

        $response = $this->deleteJson(route('inventory-movements.destroy', $inventoryMovement->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('inventory_movements', ['id' => $inventoryMovement->id]);
    }
}
