<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function order_has_many_payments()
    {
        $order = Order::factory()->create();
        $payment = Payment::factory()->create(['order_id' => $order->id]);

        $this->assertTrue($order->payments->contains($payment));
        $this->assertEquals(1, $order->payments()->count());
    }

    /** @test */
    public function order_belongs_to_customer()
    {
        $customer = Customer::factory()->create();
        $order = Order::factory()->create(['customer_id' => $customer->id]);

        $this->assertInstanceOf(Customer::class, $order->customer);
        $this->assertEquals($customer->id, $order->customer->id);
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $fillable = ['customer_id', 'order_date', 'total_amount', 'is_guest', 'status', 'payment_intent_id'];

        $this->assertEquals($fillable, (new Order)->getFillable());
    }
}
