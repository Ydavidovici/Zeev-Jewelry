<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function payment_belongs_to_order()
    {
        $order = Order::factory()->create();
        $payment = Payment::factory()->create(['order_id' => $order->id]);

        $this->assertInstanceOf(Order::class, $payment->order);
        $this->assertEquals($order->id, $payment->order->id);
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $fillable = ['order_id', 'seller_id', 'payment_intent_id', 'payment_type', 'payment_status', 'amount'];

        $this->assertEquals($fillable, (new Payment)->getFillable());
    }
}
