<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Product;

class CartItemControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_list_all_cart_items()
    {
        $cartItems = CartItem::factory()->count(3)->create();

        $response = $this->get(route('cart_items.index'));

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /** @test */
    public function can_create_a_cart_item()
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
        $response->assertJsonFragment($data);
        $this->assertDatabaseHas('cart_items', $data);
    }

    /** @test */
    public function can_show_a_cart_item()
    {
        $cartItem = CartItem::factory()->create();

        $response = $this->get(route('cart_items.show', $cartItem->id));

        $response->assertStatus(200);
        $response->assertJson($cartItem->toArray());
    }

    /** @test */
    public function can_update_a_cart_item()
    {
        $cartItem = CartItem::factory()->create();

        $data = [
            'quantity' => 5,
        ];

        $response = $this->put(route('cart_items.update', $cartItem->id), $data);

        $response->assertStatus(200);
        $response->assertJsonFragment($data);
        $this->assertDatabaseHas('cart_items', array_merge(['id' => $cartItem->id], $data));
    }

    /** @test */
    public function can_delete_a_cart_item()
    {
        $cartItem = CartItem::factory()->create();

        $response = $this->delete(route('cart_items.destroy', $cartItem->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }
}
