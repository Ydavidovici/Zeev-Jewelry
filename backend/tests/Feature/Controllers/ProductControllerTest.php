<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testProductShow()
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['product']);
    }

    public function testRecentlyViewedProducts()
    {
        $products = Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products/recently_viewed');

        $response->assertStatus(200)
            ->assertJsonStructure(['recently_viewed']);
    }
}
