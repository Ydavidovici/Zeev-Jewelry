<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\InventoryMovement;
use App\Models\Inventory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class InventoryMovementTest extends TestCase
{
    use RefreshDatabase;

    // Seed roles before each test
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    // Method to seed roles for testing
    private function seedRoles()
    {
        // Check if roles already exist to avoid duplicate entries
        if (Role::where('name', 'seller')->doesntExist()) {
            Role::create(['name' => 'seller', 'guard_name' => 'api']);
        }
        if (Role::where('name', 'customer')->doesntExist()) {
            Role::create(['name' => 'customer', 'guard_name' => 'api']);
        }
        if (Role::where('name', 'admin')->doesntExist()) {
            Role::create(['name' => 'admin', 'guard_name' => 'api']);
        }
    }

    public function test_inventory_movement_belongs_to_inventory()
    {
        $inventory = Inventory::factory()->create();
        $inventoryMovement = InventoryMovement::factory()->create(['inventory_id' => $inventory->id]);

        $this->assertInstanceOf(Inventory::class, $inventoryMovement->inventory);
        $this->assertEquals($inventory->id, $inventoryMovement->inventory->id);
    }

    public function test_inventory_movement_has_quantity()
    {
        $inventoryMovement = InventoryMovement::factory()->create(['quantity_change' => 50]);

        $this->assertEquals(50, $inventoryMovement->quantity_change);
    }
}
