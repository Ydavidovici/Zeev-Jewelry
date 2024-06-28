<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_product()
    {
        $category = Category::factory()->create(); // Create a category first
        $product = Product::factory()->create([
            'product_name' => 'Gold Necklace',
            'description' => 'A beautiful gold necklace',
            'price' => 499.99,
            'category_id' => $category->id,
        ]);

        $this->assertDatabaseHas('products', ['product_name' => 'Gold Necklace']);
    }
}
