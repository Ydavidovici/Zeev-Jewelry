<?php

namespace Tests\Feature\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class CartItemControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create(), 'api');
    }

    /** @test */
    public function it_can_view_all_cart_items()
    {
        Gate::define('viewAny', function ($user) {
            return true;
        });

        $response = $this->getJson(route('cartItems.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['cartItems']);
    }

    /** @test */
    public function it_can_add_an_item_to_the_cart()
    {
        Gate::define('create', function ($user) {
            return true;
        });

        $cart = Cart::factory()->create();
        $product = Product::factory()->create();

        $requestData = ['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 1];

        $response = $this->postJson(route('cartItems.store'), $requestData);

        $response->assertStatus(201)
            ->assertJsonFragment(['cart_id' => $cart->id, 'product_id' => $product->id]);

        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
    }

    /** @test */
    public function it_can_show_a_cart_item()
    {
        Gate::define('view', function ($user, $cartItem) {
            return true;
        });

        $cartItem = CartItem::factory()->create();

        $response = $this->getJson(route('cartItems.show', $cartItem->id));

        $response->assertStatus(200)
            ->assertJson(['id' => $cartItem->id]);
    }

    /** @test */
    public function it_can_update_a_cart_item()
    {
        Gate::define('update', function ($user, $cartItem) {
            return true;
        });

        $cartItem = CartItem::factory()->create();

        $response = $this->putJson(route('cartItems.update', $cartItem->id), ['quantity' => 3]);

        $response->assertStatus(200)
            ->assertJsonFragment(['quantity' => 3]);

        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->id,
            'quantity' => 3,
        ]);
    }

    /** @test */
    public function it_can_delete_a_cart_item()
    {
        Gate::define('delete', function ($user, $cartItem) {
            return true;
        });

        $cartItem = CartItem::factory()->create();

        $response = $this->deleteJson(route('cartItems.destroy', $cartItem->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }
}
