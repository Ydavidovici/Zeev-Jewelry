<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function payment_belongs_to_order()
    {
        $order = Order::factory()->create();
        $payment = Payment::factory()->create(['order_id' => $order->id]);

        $this->assertInstanceOf(Order::class, $payment->order);
        $this->assertEquals($order->id, $payment->order->id);
    }
}
