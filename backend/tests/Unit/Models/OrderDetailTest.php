<?php

namespace backend\tests\Unit\Models;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use backend\tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderDetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_an_order_detail()
    {
        $order = Order::factory()->create(); // Create an order first
        $product = Product::factory()->create(); // Create a product first
        $orderDetail = OrderDetail::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 50.00,
        ]);

        $this->assertDatabaseHas('order_details', ['quantity' => 2]);
    }
}
