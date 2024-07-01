<?php

namespace Tests\Unit\Models;

use App\Models\Product;
use App\Models\Inventory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InventoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_an_inventory()
    {
        $product = Product::factory()->create(); // Create a product first
        $inventory = Inventory::factory()->create([
            'product_id' => $product->id,
            'quantity' => 100,
            'location' => 'Warehouse 1',
        ]);

        $this->assertDatabaseHas('inventory', ['location' => 'Warehouse 1']);
    }
}
