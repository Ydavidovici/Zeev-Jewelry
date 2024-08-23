<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\CartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CartItemControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCartItemIndex()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        CartItem::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/cart_items');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function testCartItemStore()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/cart_items', [
                'cart_id' => 1,
                'product_id' => 1,
                'quantity' => 2,
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'cart_id', 'product_id', 'quantity']);
    }

    public function testCartItemShow()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $cartItem = CartItem::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/cart_items/{$cartItem->id}");

        $response->assertStatus(200)
            ->assertJson($cartItem->toArray());
    }

    public function testCartItemUpdate()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $cartItem = CartItem::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/cart_items/{$cartItem->id}", [
                'quantity' => 5,
            ]);

        $response->assertStatus(200)
            ->assertJson(['quantity' => 5]);
    }

    public function testCartItemDestroy()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $cartItem = CartItem::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->deleteJson("/api/cart_items/{$cartItem->id}");

        $response->assertStatus(204);
    }
}
