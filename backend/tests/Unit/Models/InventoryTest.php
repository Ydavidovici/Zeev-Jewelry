<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class InventoryTest extends TestCase
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

    public function test_inventory_belongs_to_product()
    {
        $product = Product::factory()->create();
        $inventory = Inventory::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Product::class, $inventory->product);
        $this->assertEquals($product->id, $inventory->product->id);
    }

    public function test_inventory_has_quantity()
    {
        $inventory = Inventory::factory()->create(['quantity' => 100]);

        $this->assertEquals(100, $inventory->quantity);
    }
}
