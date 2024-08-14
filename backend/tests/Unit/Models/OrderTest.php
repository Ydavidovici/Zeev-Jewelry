<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Order;

class OrderTest extends TestCase
{
    public function test_order_has_customer_id()
    {
        $order = new Order(['customer_id' => 1]);

        $this->assertEquals(1, $order->customer_id);
    }

    public function test_order_has_order_date()
    {
        $order = new Order(['order_date' => '2024-07-24']);

        $this->assertEquals('2024-07-24', $order->order_date);
    }

    public function test_order_has_total_amount()
    {
        $order = new Order(['total_amount' => 100.50]);

        $this->assertEquals(100.50, $order->total_amount);
    }

    public function test_order_is_guest()
    {
        $order = new Order(['is_guest' => true]);

        $this->assertTrue($order->is_guest);
    }

    public function test_order_has_status()
    {
        $order = new Order(['status' => 'pending']);

        $this->assertEquals('pending', $order->status);
    }
}
