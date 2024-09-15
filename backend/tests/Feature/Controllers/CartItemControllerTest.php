<?php

namespace Tests\Feature\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartItemControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Only create the role if it doesn't exist
        if (!Role::where('name', 'user')->exists()) {
            Role::create(['name' => 'user']);
        }

        // Create user and assign the 'user' role
        $user = User::factory()->create();
        $user->assignRole('user');

        $this->actingAs($user, 'api');
    }

    #[Test]
    public function it_can_view_all_cart_items()
    {
        $response = $this->getJson(route('cart_items.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([]);
    }

    #[Test]
    public function it_can_add_an_item_to_the_cart()
    {
        $cart = Cart::factory()->create();
        $product = Product::factory()->create();

        $requestData = ['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 1];

        $response = $this->postJson(route('cart_items.store'), $requestData);

        $response->assertStatus(201)
            ->assertJsonFragment(['cart_id' => $cart->id, 'product_id' => $product->id]);

        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
    }

    #[Test]
    public function it_can_show_a_cart_item()
    {
        $cartItem = CartItem::factory()->create();

        $response = $this->getJson(route('cart_items.show', $cartItem->id));

        $response->assertStatus(200)
            ->assertJson(['id' => $cartItem->id]);
    }

    #[Test]
    public function it_can_update_a_cart_item()
    {
        $cartItem = CartItem::factory()->create();

        $response = $this->putJson(route('cart_items.update', $cartItem->id), ['quantity' => 3]);

        $response->assertStatus(200)
            ->assertJsonFragment(['quantity' => 3]);

        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->id,
            'quantity' => 3,
        ]);
    }

    #[Test]
    public function it_can_delete_a_cart_item()
    {
        $cartItem = CartItem::factory()->create();

        $response = $this->deleteJson(route('cart_items.destroy', $cartItem->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }
}
