<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCartIndex()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        Cart::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/cart');

        $response->assertStatus(200)
            ->assertJsonStructure(['cart']);
    }

    public function testAddToCart()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $product = Product::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/cart', [
                'product_id' => $product->id,
                'quantity' => 2,
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'cart']);
    }

    public function testUpdateCartItem()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $cartItem = CartItem::factory()->create(['cart_id' => $cart->id]);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/cart/{$cartItem->id}", [
                'quantity' => 3,
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'cart']);
    }

    public function testRemoveFromCart()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $cartItem = CartItem::factory()->create(['cart_id' => $cart->id]);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->deleteJson("/api/cart/{$cartItem->id}");

        $response->assertStatus(204);
    }
}
