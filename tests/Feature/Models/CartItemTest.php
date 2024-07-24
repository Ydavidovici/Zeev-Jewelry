<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Product;

class CartItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_cart_item()
    {
        $cart = Cart::factory()->create();
        $product = Product::factory()->create();
        $data = [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ];

        $response = $this->post(route('cart_items.store'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('cart_items', $data);
    }

    public function test_read_cart_item()
    {
        $cartItem = CartItem::factory()->create();

        $response = $this->get(route('cart_items.show', $cartItem->id));

        $response->assertStatus(200);
        $response->assertJson($cartItem->toArray());
    }

    public function test_update_cart_item()
    {
        $cartItem = CartItem::factory()->create();
        $newQuantity = 5;
        $data = [
            'quantity' => $newQuantity,
        ];

        $response = $this->put(route('cart_items.update', $cartItem->id), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('cart_items', ['id' => $cartItem->id, 'quantity' => $newQuantity]);
    }

    public function test_delete_cart_item()
    {
        $cartItem = CartItem::factory()->create();
        $cartItemId = $cartItem->id;

        $response = $this->delete(route('cart_items.destroy', $cartItemId));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('cart_items', ['id' => $cartItemId]);
    }
}
