<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Payment;
use App\Models\Order;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_payment()
    {
        $order = Order::factory()->create();
        $data = [
            'order_id' => $order->id,
            'payment_type' => 'credit_card',
            'payment_status' => 'completed',
        ];

        $response = $this->actingAs($order->customer->user)->post(route('payments.store'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('payments', ['order_id' => $order->id, 'payment_type' => 'credit_card']);
    }

    public function test_read_payment()
    {
        $payment = Payment::factory()->create();

        $response = $this->actingAs($payment->order->customer->user)->get(route('payments.show', $payment->id));

        $response->assertStatus(200);
        $response->assertJson($payment->toArray());
    }

    public function test_update_payment()
    {
        $payment = Payment::factory()->create();
        $data = [
            'payment_status' => 'pending',
        ];

        $response = $this->actingAs($payment->order->customer->user)->put(route('payments.update', $payment->id), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('payments', ['id' => $payment->id, 'payment_status' => 'pending']);
    }

    public function test_delete_payment()
    {
        $payment = Payment::factory()->create();
        $paymentId = $payment->id;

        $response = $this->actingAs($payment->order->customer->user)->delete(route('payments.destroy', $paymentId));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('payments', ['id' => $paymentId]);
    }
}
