<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Mockery;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_a_payment_intent()
    {
        $this->actingAs(User::factory()->create());

        $order = Order::factory()->create([
            'total_amount' => 1000,
        ]);

        $response = $this->postJson('/api/payments', [
            'order_id' => $order->id,
            'amount' => $order->total_amount,
        ]);

        $response->assertStatus(200);
        $this->assertArrayHasKey('clientSecret', $response->json());

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'payment_status' => 'pending',
        ]);
    }

    /** @test */
    public function payment_can_be_confirmed()
    {
        $this->actingAs(User::factory()->create());

        $order = Order::factory()->create();

        $paymentIntent = PaymentIntent::create([
            'amount' => 1000,
            'currency' => 'usd',
        ]);

        $payment = Payment::factory()->create([
            'order_id' => $order->id,
            'payment_intent_id' => $paymentIntent->id,
            'payment_status' => 'pending',
            'amount' => 1000,
        ]);

        $response = $this->postJson('/api/payments/confirm', [
            'payment_intent_id' => $paymentIntent->id,
            'order_id' => $order->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Payment successful.']);

        $order->refresh();
        $payment->refresh();

        $this->assertEquals('Paid', $order->status);
        $this->assertEquals('succeeded', $payment->payment_status);
    }

    /** @test */
    public function user_can_update_payment_status()
    {
        $this->actingAs(User::factory()->create());

        $payment = Payment::factory()->create([
            'payment_status' => 'pending',
        ]);

        $response = $this->putJson('/api/payments/' . $payment->id, [
            'payment_status' => 'completed',
        ]);

        $response->assertStatus(200);

        $payment->refresh();
        $this->assertEquals('completed', $payment->payment_status);
    }

    /** @test */
    public function user_can_view_payment_details()
    {
        $this->actingAs(User::factory()->create());

        $payment = Payment::factory()->create();

        $response = $this->getJson('/api/payments/' . $payment->id);

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $payment->id,
        ]);
    }

    /** @test */
    public function user_can_delete_a_payment()
    {
        $this->actingAs(User::factory()->create());

        $payment = Payment::factory()->create();

        $response = $this->deleteJson('/api/payments/' . $payment->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('payments', ['id' => $payment->id]);
    }
}
