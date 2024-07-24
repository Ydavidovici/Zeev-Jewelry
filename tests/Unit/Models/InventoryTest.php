<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Inventory;

class InventoryTest extends TestCase
{
    public function test_inventory_has_product_id()
    {
        $inventory = new Inventory(['product_id' => 1]);

        $this->assertEquals(1, $inventory->product_id);
    }

    public function test_inventory_has_quantity()
    {
        $inventory = new Inventory(['quantity' => 100]);

        $this->assertEquals(100, $inventory->quantity);
    }

    public function test_inventory_has_location()
    {
        $inventory = new Inventory(['location' => 'Warehouse A']);

        $this->assertEquals('Warehouse A', $inventory->location);
    }
}
