<?php

namespace Tests\Feature\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create(), 'api');
    }

    /** @test */
    public function it_can_view_a_product()
    {
        Gate::define('view-product', function ($user) {
            return true;
        });

        $product = Product::factory()->create();

        $response = $this->getJson(route('products.show', $product->id));

        $response->assertStatus(200)
            ->assertJson(['product' => $product->toArray()]);
    }

    /** @test */
    public function it_can_store_a_product()
    {
        Gate::define('create-product', function ($user) {
            return true;
        });

        $productData = Product::factory()->make()->toArray();

        $response = $this->postJson(route('products.store'), $productData);

        $response->assertStatus(201)
            ->assertJsonStructure(['product' => ['id', 'name', 'description', 'price', 'stock']]);

        $this->assertDatabaseHas('products', $productData);
    }

    /** @test */
    public function it_cannot_store_a_product_without_proper_permission()
    {
        Gate::define('create-product', function ($user) {
            return false;
        });

        $productData = Product::factory()->make()->toArray();

        $response = $this->postJson(route('products.store'), $productData);

        $response->assertStatus(403);
    }

    /** @test */
    public function it_can_update_a_product()
    {
        Gate::define('update-product', function ($user) {
            return true;
        });

        $product = Product::factory()->create();

        $newData = ['name' => 'Updated Name'];

        $response = $this->putJson(route('products.update', $product->id), $newData);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Updated Name']);

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Updated Name']);
    }

    /** @test */
    public function it_can_delete_a_product()
    {
        Gate::define('delete-product', function ($user) {
            return true;
        });

        $product = Product::factory()->create();

        $response = $this->deleteJson(route('products.destroy', $product->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    /** @test */
    public function it_can_show_recently_viewed_products()
    {
        Gate::define('view-recently-viewed-products', function ($user) {
            return true;
        });

        $product = Product::factory()->create();
        $this->withCookie('viewed_products', json_encode([$product->id]));

        $response = $this->getJson(route('products.recentlyViewed'));

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $product->id]);
    }
}
