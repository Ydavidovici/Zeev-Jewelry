<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Payment;

class PaymentTest extends TestCase
{
    public function test_payment_has_order_id()
    {
        $payment = new Payment(['order_id' => 1]);

        $this->assertEquals(1, $payment->order_id);
    }

    public function test_payment_has_payment_type()
    {
        $payment = new Payment(['payment_type' => 'credit_card']);

        $this->assertEquals('credit_card', $payment->payment_type);
    }

    public function test_payment_has_payment_status()
    {
        $payment = new Payment(['payment_status' => 'completed']);

        $this->assertEquals('completed', $payment->payment_status);
    }
}
