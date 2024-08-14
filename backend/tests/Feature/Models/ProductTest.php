<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_product()
    {
        $category = Category::factory()->create();
        $data = [
            'category_id' => $category->id,
            'product_name' => 'Sample Product',
            'description' => 'Sample Description',
            'price' => 99.99,
            'image_url' => 'http://example.com/image.jpg',
        ];

        $response = $this->post(route('products.store'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('products', ['product_name' => 'Sample Product']);
    }

    public function test_read_product()
    {
        $product = Product::factory()->create();

        $response = $this->get(route('products.show', $product->id));

        $response->assertStatus(200);
        $response->assertJson($product->toArray());
    }

    public function test_update_product()
    {
        $product = Product::factory()->create();
        $data = [
            'product_name' => 'Updated Product',
            'description' => 'Updated Description',
        ];

        $response = $this->put(route('products.update', $product->id), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'product_name' => 'Updated Product']);
    }

    public function test_delete_product()
    {
        $product = Product::factory()->create();
        $productId = $product->id;

        $response = $this->delete(route('products.destroy', $productId));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('products', ['id' => $productId]);
    }
}
