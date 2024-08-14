<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_add_product_to_cart()
    {
        $product = Product::factory()->create();

        $response = $this->postJson('/cart', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Product added to cart.']);

        $this->assertNotNull($response->headers->getCookies());
        $this->assertArrayHasKey($product->id, session("cart_" . $this->getCookie('cart_id')));
    }

    public function test_can_update_cart_item()
    {
        $product = Product::factory()->create();

        $this->postJson('/cart', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->putJson("/cart/{$product->id}", [
            'quantity' => 5,
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Cart updated.']);

        $this->assertEquals(5, session("cart_" . $this->getCookie('cart_id'))[$product->id]['quantity']);
    }

    public function test_can_remove_product_from_cart()
    {
        $product = Product::factory()->create();

        $this->postJson('/cart', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->deleteJson("/cart/{$product->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Product removed from cart.']);

        $this->assertArrayNotHasKey($product->id, session("cart_" . $this->getCookie('cart_id')));
    }
}
