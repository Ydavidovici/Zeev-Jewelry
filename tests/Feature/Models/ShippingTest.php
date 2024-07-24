<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Shipping;
use App\Models\Order;

class ShippingTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_shipping()
    {
        $order = Order::factory()->create();
        $data = [
            'order_id' => $order->id,
            'address' => '123 Main St',
            'city' => 'New York',
            'state' => 'NY',
            'postal_code' => '10001',
            'country' => 'USA',
            'shipping_method' => 'FedEx',
            'tracking_number' => '123456789',
            'status' => 'shipped',
        ];

        $response = $this->post(route('shipping.store'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('shipping', ['order_id' => $order->id, 'address' => '123 Main St']);
    }

    public function test_read_shipping()
    {
        $shipping = Shipping::factory()->create();

        $response = $this->get(route('shipping.show', $shipping->id));

        $response->assertStatus(200);
        $response->assertJson($shipping->toArray());
    }

    public function test_update_shipping()
    {
        $shipping = Shipping::factory()->create();
        $data = [
            'status' => 'delivered',
        ];

        $response = $this->put(route('shipping.update', $shipping->id), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('shipping', ['id' => $shipping->id, 'status' => 'delivered']);
    }

    public function test_delete_shipping()
    {
        $shipping = Shipping::factory()->create();
        $shippingId = $shipping->id;

        $response = $this->delete(route('shipping.destroy', $shippingId));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('shipping', ['id' => $shippingId]);
    }
}
