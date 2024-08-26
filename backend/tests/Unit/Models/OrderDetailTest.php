<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class OrderDetailTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function order_detail_belongs_to_order()
    {
        $order = Order::factory()->create();
        $product = Product::factory()->create(); // Ensure a product is created
        $orderDetail = OrderDetail::factory()->create(['order_id' => $order->id, 'product_id' => $product->id]);

        $this->assertInstanceOf(Order::class, $orderDetail->order);
        $this->assertEquals($order->id, $orderDetail->order->id);
    }

    #[Test]
    public function order_detail_belongs_to_product()
    {
        $product = Product::factory()->create();
        $orderDetail = OrderDetail::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Product::class, $orderDetail->product); // Verify the relationship
        $this->assertEquals($product->id, $orderDetail->product->id);
    }

    #[Test]
    public function order_detail_has_quantity()
    {
        $orderDetail = OrderDetail::factory()->create(['quantity' => 3]);

        $this->assertEquals(3, $orderDetail->quantity);
    }

    #[Test]
    public function order_detail_has_price()
    {
        $orderDetail = OrderDetail::factory()->create(['price' => 29.99]);

        $this->assertEquals(29.99, $orderDetail->price);
    }
}
