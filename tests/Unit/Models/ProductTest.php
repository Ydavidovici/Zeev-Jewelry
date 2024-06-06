<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_product()
    {
        $product = Product::factory()->create([
            'product_name' => 'Gold Necklace',
            'description' => 'A beautiful gold necklace',
            'price' => 499.99,
            'category_id' => 1,
        ]);

        $this->assertDatabaseHas('products', ['product_name' => 'Gold Necklace']);
    }
}
