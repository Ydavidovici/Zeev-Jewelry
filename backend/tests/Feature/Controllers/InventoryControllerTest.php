<?php

namespace Tests\Feature\Controllers;

use App\Models\Inventory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class InventoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create(), 'api');
    }

    /** @test */
    public function it_can_view_all_inventories()
    {
        Gate::define('viewAny', function ($user) {
            return true;
        });

        $response = $this->getJson(route('inventories.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['*' => ['id', 'product_id', 'quantity', 'location']]);
    }

    /** @test */
    public function it_can_create_inventory()
    {
        Gate::define('create', function ($user) {
            return true;
        });

        $inventoryData = [
            'product_id' => 1,
            'quantity' => 10,
            'location' => 'Warehouse A'
        ];

        $response = $this->postJson(route('inventories.store'), $inventoryData);

        $response->assertStatus(201)
            ->assertJsonFragment(['location' => 'Warehouse A']);

        $this->assertDatabaseHas('inventories', ['product_id' => 1, 'location' => 'Warehouse A']);
    }

    /** @test */
    public function it_can_show_inventory()
    {
        Gate::define('view', function ($user, $inventory) {
            return true;
        });

        $inventory = Inventory::factory()->create();

        $response = $this->getJson(route('inventories.show', $inventory->id));

        $response->assertStatus(200)
            ->assertJson(['id' => $inventory->id]);
    }

    /** @test */
    public function it_can_update_inventory()
    {
        Gate::define('update', function ($user, $inventory) {
            return true;
        });

        $inventory = Inventory::factory()->create();

        $response = $this->putJson(route('inventories.update', $inventory->id), ['quantity' => 15]);

        $response->assertStatus(200)
            ->assertJsonFragment(['quantity' => 15]);

        $this->assertDatabaseHas('inventories', ['id' => $inventory->id, 'quantity' => 15]);
    }

    /** @test */
    public function it_can_delete_inventory()
    {
        Gate::define('delete', function ($user, $inventory) {
            return true;
        });

        $inventory = Inventory::factory()->create();

        $response = $this->deleteJson(route('inventories.destroy', $inventory->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('inventories', ['id' => $inventory->id]);
    }
}
