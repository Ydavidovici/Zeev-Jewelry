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
            'shipping_type' => 'FedEx',
            'shipping_cost' => 10.50,
            'shipping_status' => 'shipped',
            'tracking_number' => '123456789',
            'shipping_address' => '123 Main St',
            'shipping_carrier' => 'FedEx',
            'recipient_name' => 'John Doe',
            'estimated_delivery_date' => now()->addDays(3),
            'additional_notes' => 'Handle with care',
        ];

        $response = $this->actingAs($order->customer->user)->post(route('shippings.store'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('shippings', ['order_id' => $order->id, 'shipping_address' => '123 Main St']);
    }

    public function test_read_shipping()
    {
        $shipping = Shipping::factory()->create();

        $response = $this->actingAs($shipping->order->customer->user)->get(route('shippings.show', $shipping->id));

        $response->assertStatus(200);
        $response->assertJson($shipping->toArray());
    }

    public function test_update_shipping()
    {
        $shipping = Shipping::factory()->create();
        $data = [
            'shipping_status' => 'delivered',
        ];

        $response = $this->actingAs($shipping->order->customer->user)->put(route('shippings.update', $shipping->id), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('shippings', ['id' => $shipping->id, 'shipping_status' => 'delivered']);
    }

    public function test_delete_shipping()
    {
        $shipping = Shipping::factory()->create();
        $shippingId = $shipping->id;

        $response = $this->actingAs($shipping->order->customer->user)->delete(route('shippings.destroy', $shippingId));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('shippings', ['id' => $shippingId]);
    }
}
