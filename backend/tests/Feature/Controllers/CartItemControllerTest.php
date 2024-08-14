<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\CartItem;
use App\Models\User;
use App\Models\Cart;
use App\Models\Product;

class CartItemControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_cart_items()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/cart_items');

        $response->assertStatus(200)
            ->assertJsonStructure([[]]); // Assuming you expect an array of cart items
    }

    public function test_user_can_create_cart_item()
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->create();
        $product = Product::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'cart_id', 'product_id', 'quantity']);
    }

    public function test_user_can_view_single_cart_item()
    {
        $user = User::factory()->create();
        $cartItem = CartItem::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson("/api/cart_items/{$cartItem->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'cart_id', 'product_id', 'quantity']);
    }

    public function test_user_can_update_cart_item()
    {
        $user = User::factory()->create();
        $cartItem = CartItem::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->putJson("/api/cart_items/{$cartItem->id}", [
            'quantity' => 2,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'cart_id', 'product_id', 'quantity']);
    }

    public function test_user_can_delete_cart_item()
    {
        $user = User::factory()->create();
        $cartItem = CartItem::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->deleteJson("/api/cart_items/{$cartItem->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }
}
