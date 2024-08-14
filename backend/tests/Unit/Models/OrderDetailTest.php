<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\OrderDetail;

class OrderDetailTest extends TestCase
{
    public function test_order_detail_has_order_id()
    {
        $orderDetail = new OrderDetail(['order_id' => 1]);

        $this->assertEquals(1, $orderDetail->order_id);
    }

    public function test_order_detail_has_product_id()
    {
        $orderDetail = new OrderDetail(['product_id' => 1]);

        $this->assertEquals(1, $orderDetail->product_id);
    }

    public function test_order_detail_has_quantity()
    {
        $orderDetail = new OrderDetail(['quantity' => 2]);

        $this->assertEquals(2, $orderDetail->quantity);
    }

    public function test_order_detail_has_price()
    {
        $orderDetail = new OrderDetail(['price' => 99.99]);

        $this->assertEquals(99.99, $orderDetail->price);
    }
}
