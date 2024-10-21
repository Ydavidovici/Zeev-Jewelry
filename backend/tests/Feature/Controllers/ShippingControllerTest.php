<?php

namespace Tests\Feature\Controllers;

use App\Models\Order;
use App\Models\Shipping;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;

class ShippingControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();

        // Create a user with the role 'seller'
        $this->seller = User::factory()->create();
        $this->seller->assignRole('seller');

        // Create a user with the role 'admin'
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        // Authenticate as a seller by default
        $this->actingAs($this->seller, 'api');
    }

    /** @test */
    public function it_can_view_all_shipping_details_as_seller()
    {
        $response = $this->actingAs($this->seller, 'api')->getJson(route('shipping.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['*' => ['id', 'order_id', 'shipping_type', 'shipping_cost', 'shipping_status']]);
    }

    /** @test */
    public function it_can_create_shipping_details_as_seller()
    {
        // Dynamically create an order for the seller
        $order = Order::factory()->create(['seller_id' => $this->seller->id]);

        $shippingData = [
            'order_id' => $order->id,
            'seller_id' => $this->seller->id,
            'shipping_type' => 'Standard',
            'shipping_cost' => 10.00,
            'shipping_status' => 'Pending',
            'tracking_number' => '123456',
            'shipping_address' => '123 Main St',
            'shipping_carrier' => 'DHL',
            'recipient_name' => 'John Doe',
            'city' => 'New York',
            'state' => 'NY',
            'postal_code' => '01234',
            'country' => 'US',
            'shipping_method' => 'Ground',  // Add this line
            'estimated_delivery_date' => now()->addWeek()->toDateString(),
        ];


        $response = $this->actingAs($this->seller, 'api')->postJson(route('shipping.store'), $shippingData);

        $response->assertStatus(201)
            ->assertJsonFragment(['shipping_status' => 'Pending']);

        $this->assertDatabaseHas('shipping', ['order_id' => $order->id, 'shipping_status' => 'Pending']);
    }

    /** @test */
    public function it_can_show_shipping_details_as_seller()
    {
        $shipping = Shipping::factory()->create(['seller_id' => $this->seller->id]);

        $response = $this->actingAs($this->seller, 'api')->getJson(route('shipping.show', $shipping->id));

        $response->assertStatus(200)
            ->assertJson(['id' => $shipping->id]);
    }

    /** @test */
    public function it_can_update_shipping_details_as_seller()
    {
        // Create an initial shipping record with a pending status
        $shipping = Shipping::factory()->create([
            'shipping_status' => 'pending',
            'seller_id' => $this->seller->id,
        ]);

        // Perform the update request
        $response = $this->actingAs($this->seller, 'api')
            ->putJson(route('shipping.update', $shipping->id), ['shipping_status' => 'Shipped']);

        // Assert the update was successful
        $response->assertStatus(200)
            ->assertJsonFragment(['shipping_status' => 'Shipped']);

        // Check the database for the updated status
        $this->assertDatabaseHas('shipping', ['id' => $shipping->id, 'shipping_status' => 'Shipped']);
    }

    /** @test */
    public function it_can_delete_shipping_details_as_seller()
    {
        $shipping = Shipping::factory()->create(['seller_id' => $this->seller->id]);

        $response = $this->actingAs($this->seller, 'api')->deleteJson(route('shipping.destroy', $shipping->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('shipping', ['id' => $shipping->id]);
    }

    /** @test */
    public function admin_can_view_shipping_details()
    {
        $shipping = Shipping::factory()->create(['seller_id' => $this->seller->id]);

        $response = $this->actingAs($this->admin, 'api')->getJson(route('shipping.show', $shipping->id));

        $response->assertStatus(200)
            ->assertJson(['id' => $shipping->id]);
    }
}
