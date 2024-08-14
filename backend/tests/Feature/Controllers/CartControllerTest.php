<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_cart()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/cart');

        $response->assertStatus(200)
            ->assertJsonStructure(['cart']);
    }

    public function test_user_can_add_product_to_cart()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/cart', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Product added to cart.']);
    }

    public function test_user_can_update_cart()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $this->actingAs($user, 'sanctum');

        $this->postJson('/api/cart', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->putJson("/api/cart/{$product->id}", [
            'quantity' => 2,
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Cart updated.']);
    }

    public function test_user_can_remove_product_from_cart()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $this->actingAs($user, 'sanctum');

        $this->postJson('/api/cart', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->deleteJson("/api/cart/{$product->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Product removed from cart.']);
    }
}
