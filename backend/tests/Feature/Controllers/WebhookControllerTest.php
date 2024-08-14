<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class WebhookControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_webhook_handles_payment_intent_succeeded()
    {
        $order = Order::factory()->create(['payment_intent_id' => 'pi_test']);

        $payload = json_encode([
            'type' => 'payment_intent.succeeded',
            'data' => [
                'object' => [
                    'id' => 'pi_test',
                    'amount_received' => 1000,
                ],
            ],
        ]);

        $signature = Webhook::generateSignature($payload, env('STRIPE_WEBHOOK_SECRET'));

        $response = $this->withHeader('Stripe-Signature', $signature)
            ->postJson('/api/stripe/webhook', [], $payload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'Paid']);
    }

    public function test_webhook_returns_error_for_invalid_signature()
    {
        $response = $this->withHeader('Stripe-Signature', 'invalid_signature')
            ->postJson('/api/stripe/webhook', []);

        $response->assertStatus(400)
            ->assertJson(['error' => 'Invalid signature']);
    }

    public function test_webhook_returns_error_for_invalid_payload()
    {
        $response = $this->postJson('/api/stripe/webhook', 'invalid_payload');

        $response->assertStatus(400)
            ->assertJson(['error' => 'Invalid payload']);
    }
}
