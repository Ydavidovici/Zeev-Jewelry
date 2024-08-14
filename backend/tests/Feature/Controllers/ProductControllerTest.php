<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_product_and_track_recently_viewed()
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson(['product' => [
                'id' => $product->id,
            ]]);

        $this->assertContains($product->id, json_decode($this->getCookie('viewed_products'), true));
    }

    public function test_can_view_recently_viewed_products()
    {
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        $this->withCookie('viewed_products', json_encode([$product1->id, $product2->id]))
            ->getJson('/products/recently-viewed')
            ->assertStatus(200)
            ->assertJson(['recently_viewed' => [
                ['id' => $product1->id],
                ['id' => $product2->id],
            ]]);
    }
}
