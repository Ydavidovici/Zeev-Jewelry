<?php

namespace Tests\Feature\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create()->assignRole('customer'), 'api');  // Ensure user is a customer
    }

    public function test_it_can_view_the_checkout_cart()
    {
        session()->put('cart', [
            ['product' => Product::factory()->create(), 'quantity' => 1]
        ]);

        $response = $this->getJson(route('checkout.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['cart']);
    }

    public function test_it_can_create_an_order()
    {
        $seller = User::factory()->create()->assignRole('seller');
        $product = Product::factory()->create(['seller_id' => $seller->id, 'stock_quantity' => 50]);

        session()->put('cart', [
            ['product' => $product, 'quantity' => 1]
        ]);

        $orderData = [
            'products' => [
                ['id' => $product->id, 'quantity' => 1],
            ],
            'shipping_address' => '123 Main St',
            'city' => 'Testville', // Ensure the city field is included
            'state' => 'Test State',
            'postal_code' => '12345',
            'country' => 'Test Country',
            'shipping_type' => 'Express',
            'shipping_cost' => 10.50,
            'shipping_carrier' => 'Test Carrier',
            'shipping_method' => 'Air',
            'recipient_name' => 'Test Recipient',
            'estimated_delivery_date' => now()->addDays(5)->format('Y-m-d H:i:s'), // Ensure proper format
        ];

        $response = $this->postJson(route('checkout.store'), $orderData);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Order placed successfully.']);

        $this->assertDatabaseHas('orders', [
            'customer_id' => auth()->id(),
            'status' => 'Pending',
        ]);

        $this->assertDatabaseHas('shipping', [
            'shipping_address' => '123 Main St',
            'city' => 'Testville', // Check if the city field is present in the database
        ]);
    }


    public function test_it_can_confirm_checkout_success()
    {
        $response = $this->getJson(route('checkout.success'));

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Order completed successfully.']);
    }

    public function test_it_can_confirm_checkout_failure()
    {
        $response = $this->getJson(route('checkout.failure'));

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Order failed.']);
    }
}
