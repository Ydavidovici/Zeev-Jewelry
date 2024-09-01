<?php

namespace Tests\Feature\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create(), 'api');
    }

    /** @test */
    public function it_can_view_the_cart()
    {
        Gate::define('viewAny', function ($user) {
            return true;
        });

        $response = $this->getJson(route('carts.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['cart']);
    }

    /** @test */
    public function it_can_add_a_product_to_the_cart()
    {
        Gate::define('create', function ($user) {
            return true;
        });

        $product = Product::factory()->create();
        $requestData = ['product_id' => $product->id, 'quantity' => 1];

        $response = $this->postJson(route('carts.store'), $requestData);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Product added to cart.']);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
    }

    /** @test */
    public function it_can_update_cart_item_quantity()
    {
        Gate::define('update', function ($user, $cart) {
            return true;
        });

        $cartItem = CartItem::factory()->create();

        $response = $this->putJson(route('carts.update', $cartItem->id), ['quantity' => 2]);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Cart updated.']);

        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->id,
            'quantity' => 2,
        ]);
    }

    /** @test */
    public function it_can_remove_a_product_from_the_cart()
    {
        Gate::define('delete', function ($user, $cart) {
            return true;
        });

        $cartItem = CartItem::factory()->create();

        $response = $this->deleteJson(route('carts.destroy', $cartItem->id));

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Product removed from cart.']);

        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }
}
