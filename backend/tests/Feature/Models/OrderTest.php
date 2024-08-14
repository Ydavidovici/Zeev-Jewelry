<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Order;
use App\Models\Customer;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_order()
    {
        $customer = Customer::factory()->create();
        $data = [
            'customer_id' => $customer->id,
            'order_date' => now(),
            'total_amount' => 100.50,
            'is_guest' => false,
            'status' => 'pending',
        ];

        $response = $this->actingAs($customer->user)->post(route('orders.store'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('orders', ['customer_id' => $customer->id, 'total_amount' => 100.50]);
    }

    public function test_read_order()
    {
        $order = Order::factory()->create();

        $response = $this->actingAs($order->customer->user)->get(route('orders.show', $order->id));

        $response->assertStatus(200);
        $response->assertJson($order->toArray());
    }

    public function test_update_order()
    {
        $order = Order::factory()->create();
        $data = [
            'total_amount' => 150.75,
            'status' => 'completed',
        ];

        $response = $this->actingAs($order->customer->user)->put(route('orders.update', $order->id), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'total_amount' => 150.75, 'status' => 'completed']);
    }

    public function test_delete_order()
    {
        $order = Order::factory()->create();
        $orderId = $order->id;

        $response = $this->actingAs($order->customer->user)->delete(route('orders.destroy', $orderId));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('orders', ['id' => $orderId]);
    }
}
