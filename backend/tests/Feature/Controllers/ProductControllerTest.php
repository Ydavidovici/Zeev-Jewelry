<?php

namespace Tests\Feature\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $seller;

    protected function setUp(): void
    {
        parent::setUp();

        // Assume the roles and users are seeded via a seeder in the BaseTestCase
        $this->seller = User::factory()->create();
        $this->seller->assignRole('seller');

        $this->actingAs($this->seller, 'api');
    }

    public function test_it_can_view_a_product()
    {
        $product = Product::factory()->create();

        $response = $this->getJson(route('products.show', $product->id));

        $response->assertStatus(200)
            ->assertJson(['product' => $product->toArray()]);
    }

    public function test_it_can_store_a_product()
    {
        $productData = Product::factory()->make()->toArray();

        // Set a default image URL in the test data
        $productData['image_url'] = 'path/to/default-image.jpg';

        $response = $this->postJson(route('products.store'), $productData);

        $response->assertStatus(201)
            ->assertJsonStructure(['product' => ['id', 'name', 'description', 'price', 'stock_quantity', 'image_url']]);

        $this->assertDatabaseHas('products', [
            'seller_id' => $productData['seller_id'],
            'name' => $productData['name'],
            'description' => $productData['description'],
            'price' => $productData['price'],
            'category_id' => $productData['category_id'],
            'stock_quantity' => $productData['stock_quantity'],
            'image_url' => $productData['image_url'],  // Assert the image URL is correctly stored
        ]);
    }


    public function test_non_seller_cannot_store_a_product()
    {
        $nonSeller = User::factory()->create(); // User without a seller role
        $this->actingAs($nonSeller, 'api');

        $productData = Product::factory()->make()->toArray();

        $response = $this->postJson(route('products.store'), $productData);

        $response->assertStatus(403);
    }

    public function test_it_can_update_a_product()
    {
        $product = Product::factory()->create();

        $newData = ['name' => 'Updated Name'];

        $response = $this->putJson(route('products.update', $product->id), $newData);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Updated Name']);

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Updated Name']);
    }

    public function test_it_can_delete_a_product()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson(route('products.destroy', $product->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
