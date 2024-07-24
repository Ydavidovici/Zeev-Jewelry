<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Product;

class OrderDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_order_detail()
    {
        $order = Order::factory()->create();
        $product = Product::factory()->create();
        $data = [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 99.99,
        ];

        $response = $this->post(route('order_details.store'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('order_details', ['order_id' => $order->id, 'product_id' => $product->id]);
    }

    public function test_read_order_detail()
    {
        $orderDetail = OrderDetail::factory()->create();

        $response = $this->get(route('order_details.show', $orderDetail->id));

        $response->assertStatus(200);
        $response->assertJson($orderDetail->toArray());
    }

    public function test_update_order_detail()
    {
        $orderDetail = OrderDetail::factory()->create();
        $data = [
            'quantity' => 5,
            'price' => 199.99,
        ];

        $response = $this->put(route('order_details.update', $orderDetail->id), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('order_details', ['id' => $orderDetail->id, 'quantity' => 5, 'price' => 199.99]);
    }

    public function test_delete_order_detail()
    {
        $orderDetail = OrderDetail::factory()->create();
        $orderDetailId = $orderDetail->id;

        $response = $this->delete(route('order_details.destroy', $orderDetailId));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('order_details', ['id' => $orderDetailId]);
    }
}
