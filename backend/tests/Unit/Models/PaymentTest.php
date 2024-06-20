<?php

namespace backend\tests\Unit\Models;

use App\Models\Order;
use App\Models\Payment;
use backend\tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_payment()
    {
        $order = Order::factory()->create(); // Create an order first
        $payment = Payment::factory()->create([
            'order_id' => $order->id,
            'payment_type' => 'Credit Card',
            'payment_status' => 'processed',
        ]);

        $this->assertDatabaseHas('payments', ['payment_type' => 'Credit Card']);
    }
}
