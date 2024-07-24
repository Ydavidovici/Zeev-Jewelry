<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;

class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and set them as the current authenticated user
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function user_can_view_checkout_page()
    {
        $response = $this->get(route('checkout.index'));

        $response->assertStatus(200);
        $response->assertViewIs('checkout.index');
        $response->assertViewHas('cart');
    }

    /** @test */
    public function user_can_place_an_order()
    {
        $product = Product::factory()->create(['price' => 100]);

        $cart = [
            $product->id => [
                'product' => $product,
                'quantity' => 2,
            ],
        ];

        Session::put('cart', $cart);

        $data = [
            'address' => '123 Test Street',
            'city' => 'Test City',
            'postal_code' => '12345',
        ];

        $response = $this->post(route('checkout.store'), $data);

        $response->assertRedirect(route('checkout.success'));
        $response->assertSessionHas('success', 'Order placed successfully.');

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'address' => '123 Test Street',
            'city' => 'Test City',
            'postal_code' => '12345',
            'status' => 'Pending',
        ]);

        $order = Order::first();
        $this->assertDatabaseHas('order_details', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 100,
        ]);

        $this->assertEmpty(Session::get('cart'));
    }

    /** @test */
    public function user_cannot_place_order_with_empty_cart()
    {
        $data = [
            'address' => '123 Test Street',
            'city' => 'Test City',
            'postal_code' => '12345',
        ];

        $response = $this->post(route('checkout.store'), $data);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error', 'Your cart is empty.');
    }

    /** @test */
    public function user_sees_success_page_after_placing_order()
    {
        $response = $this->get(route('checkout.success'));

        $response->assertStatus(200);
        $response->assertViewIs('checkout.success');
    }

    /** @test */
    public function user_sees_failure_page_after_order_fails()
    {
        $response = $this->get(route('checkout.failure'));

        $response->assertStatus(200);
        $response->assertViewIs('checkout.failure');
    }
}
