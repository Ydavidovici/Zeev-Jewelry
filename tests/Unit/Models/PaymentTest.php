<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_payment()
    {
        $payment = Payment::factory()->create([
            'order_id' => 1,
            'payment_type' => 'Credit Card',
            'payment_status' => 'processed',
        ]);

        $this->assertDatabaseHas('payments', ['payment_type' => 'Credit Card']);
    }
}
