<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function order_has_many_payments()
    {
        $order = Order::factory()->create();
        $payment = Payment::factory()->create(['order_id' => $order->id]);

        $this->assertTrue($order->payments->contains($payment));
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $order->payments);
    }

    #[Test]
    public function order_belongs_to_customer()
    {
        $customer = Customer::factory()->create();
        $order = Order::factory()->create(['customer_id' => $customer->id]);

        $this->assertInstanceOf(Customer::class, $order->customer);
        $this->assertEquals($customer->id, $order->customer->id);
    }
}
