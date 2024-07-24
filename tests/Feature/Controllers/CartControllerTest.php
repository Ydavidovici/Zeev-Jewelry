<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;
use App\Models\Product;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_cart()
    {
        $response = $this->get(route('cart.index'));

        $response->assertStatus(200);
        $response->assertViewIs('cart.index');
        $response->assertViewHas('cart');
    }

    /** @test */
    public function user_can_add_product_to_cart()
    {
        $product = Product::factory()->create();

        $data = [
            'product_id' => $product->id,
            'quantity' => 2,
        ];

        $response = $this->post(route('cart.store'), $data);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('success', 'Product added to cart.');

        $this->assertEquals(2, Session::get("cart.{$product->id}.quantity"));
        $this->assertEquals($product->id, Session::get("cart.{$product->id}.product")->id);
    }

    /** @test */
    public function user_can_update_cart()
    {
        $product = Product::factory()->create();
        Session::put('cart', [
            $product->id => [
                'product' => $product,
                'quantity' => 2,
            ]
        ]);

        $data = [
            'quantity' => 5,
        ];

        $response = $this->put(route('cart.update', $product->id), $data);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('success', 'Cart updated.');

        $this->assertEquals(5, Session::get("cart.{$product->id}.quantity"));
    }

    /** @test */
    public function user_can_remove_product_from_cart()
    {
        $product = Product::factory()->create();
        Session::put('cart', [
            $product->id => [
                'product' => $product,
                'quantity' => 2,
            ]
        ]);

        $response = $this->delete(route('cart.destroy', $product->id));

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('success', 'Product removed from cart.');

        $this->assertNull(Session::get("cart.{$product->id}"));
    }
}
