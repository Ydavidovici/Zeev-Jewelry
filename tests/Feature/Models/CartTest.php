<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Cart;
use App\Models\User;
use App\Models\CartItem;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_cart()
    {
        $user = User::factory()->create();
        $data = [
            'user_id' => $user->id,
        ];

        $response = $this->post(route('carts.store'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('carts', ['user_id' => $user->id]);
    }

    public function test_read_cart()
    {
        $cart = Cart::factory()->create();

        $response = $this->get(route('carts.show', $cart->id));

        $response->assertStatus(200);
        $response->assertJson($cart->toArray());
    }

    public function test_update_cart()
    {
        $cart = Cart::factory()->create();
        $newUser = User::factory()->create();
        $data = [
            'user_id' => $newUser->id,
        ];

        $response = $this->put(route('carts.update', $cart->id), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('carts', ['id' => $cart->id, 'user_id' => $newUser->id]);
    }

    public function test_delete_cart()
    {
        $cart = Cart::factory()->create();
        $cartId = $cart->id;

        $response = $this->delete(route('carts.destroy', $cartId));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('carts', ['id' => $cartId]);
    }
}
