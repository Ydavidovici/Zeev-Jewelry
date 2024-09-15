<?php

namespace Tests\Feature\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $role = Role::create(['name' => 'user']);

        // Create and assign role to user
        $user = User::factory()->create();
        $user->assignRole($role);

        $this->actingAs($user, 'api');
    }

    #[Test]
    public function it_can_view_the_cart()
    {
        $response = $this->getJson(route('cart.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['cart']);
    }

    #[Test]
    public function it_can_add_a_product_to_the_cart()
    {
        $product = Product::factory()->create();
        $requestData = ['product_id' => $product->id, 'quantity' => 1];

        $response = $this->postJson(route('cart.store'), $requestData);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Product added to cart.']);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
    }

    #[Test]
    public function it_can_update_cart_item_quantity()
    {
        // Create a user and authenticate them
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        // Create a cart belonging to the authenticated user
        $cart = Cart::factory()->create(['user_id' => $user->id]);

        // Create a cart item within that cart
        $cartItem = CartItem::factory()->create(['cart_id' => $cart->id]);

        // Attempt to update the quantity of the cart item
        $response = $this->putJson(route('cart.update', $cartItem->id), ['quantity' => 2]);

        // Assert the response status is 200 OK
        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Cart updated.']);

        // Verify that the cart item was updated in the database
        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->id,
            'quantity' => 2,
        ]);
    }

    #[Test]
    public function it_can_remove_a_product_from_the_cart()
    {
        // Create a user and authenticate them
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        // Create a cart belonging to the authenticated user
        $cart = Cart::factory()->create(['user_id' => $user->id]);

        // Create a cart item within that cart
        $cartItem = CartItem::factory()->create(['cart_id' => $cart->id]);

        // Attempt to remove the product from the cart
        $response = $this->deleteJson(route('cart.destroy', $cartItem->id));

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Product removed from cart.']);

        // Verify that the cart item is deleted from the database
        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }
}
