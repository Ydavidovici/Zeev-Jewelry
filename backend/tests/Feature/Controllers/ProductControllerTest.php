<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_products()
    {
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure([[]]); // Expect an array of products
    }

    public function test_user_can_create_product()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/products', [
            'name' => 'Test Product',
            'description' => 'Product description',
            'price' => 100,
            'stock_quantity' => 10,
            'is_featured' => false,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'name', 'description', 'price', 'stock_quantity', 'is_featured']);
    }

    public function test_user_can_view_single_product()
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'description', 'price', 'stock_quantity', 'is_featured']);
    }

    public function test_user_can_update_product()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $product = Product::factory()->create();

        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => 'Updated Product',
            'description' => 'Updated description',
            'price' => 150,
            'stock_quantity' => 20,
            'is_featured' => true,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'description', 'price', 'stock_quantity', 'is_featured']);
    }

    public function test_user_can_delete_product()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
