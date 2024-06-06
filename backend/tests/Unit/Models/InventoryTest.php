<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Inventory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InventoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_an_inventory()
    {
        $inventory = Inventory::factory()->create([
            'product_id' => 1,
            'quantity' => 100,
            'location' => 'Warehouse 1',
        ]);

        $this->assertDatabaseHas('inventories', ['location' => 'Warehouse 1']);
    }
}
