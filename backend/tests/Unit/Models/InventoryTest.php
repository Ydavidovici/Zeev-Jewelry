<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class InventoryTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function inventory_belongs_to_product()
    {
        $product = Product::factory()->create();
        $inventory = Inventory::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Product::class, $inventory->product);
        $this->assertEquals($product->id, $inventory->product->id);
    }

    #[Test]
    public function inventory_has_quantity()
    {
        $inventory = Inventory::factory()->create(['quantity' => 100]);

        $this->assertEquals(100, $inventory->quantity);
    }
}
