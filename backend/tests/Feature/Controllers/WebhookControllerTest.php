<?php

namespace Tests\Feature\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebhookControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_handle_a_successful_payment_intent()
    {
        // Create a test order and payment
        $order = Order::factory()->create(['status' => 'Pending']);
        Payment::factory()->create([
            'order_id' => $order->id,
            'payment_status' => 'Pending',
            'stripe_payment_id' => 'pi_test_123',
        ]);

        // Create a mock Stripe event payload
        $payload = json_encode([
            'type' => 'payment_intent.succeeded',
            'data' => [
                'object' => [
                    'id' => 'pi_test_123',
                    'amount_received' => 23807, // Stripe uses amounts in cents
                    'status' => 'succeeded',
                ]
            ]
        ]);

        // Generate a valid Stripe signature
        $timestamp = time();
        $secret = config('services.stripe.webhook_secret');
        $signed_payload = "{$timestamp}.{$payload}";
        $signature = hash_hmac('sha256', $signed_payload, $secret);

        // Set the Stripe-Signature header
        $sig_header = "t={$timestamp},v1={$signature}";

        // Perform the request with the valid signature
        $response = $this->withHeaders(['Stripe-Signature' => $sig_header])
            ->postJson(route('webhook.handle'), json_decode($payload, true));

        // Assert that the response is successful
        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        // Assert that the order and payment were updated in the database
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'Paid']);
        $this->assertDatabaseHas('payments', ['order_id' => $order->id, 'payment_status' => 'succeeded']);
    }
}
