<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use App\Models\Order;
use Stripe\Webhook;

class WebhookControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Fake Stripe's Webhook
        Event::fake();
    }

    /** @test */
    public function it_handles_payment_intent_succeeded_webhook()
    {
        Log::shouldReceive('info')->once()->with('PaymentIntent was successful!');

        $order = Order::factory()->create(['payment_intent_id' => 'pi_12345', 'status' => 'Pending']);

        $payload = json_encode([
            'type' => 'payment_intent.succeeded',
            'data' => [
                'object' => [
                    'id' => 'pi_12345',
                ],
            ],
        ]);

        $signature = $this->generateStripeSignature($payload);

        $response = $this->postJson(route('webhook.handle'), $payload, [
            'Stripe-Signature' => $signature,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'Paid',
        ]);
    }

    /** @test */
    public function it_returns_error_for_invalid_payload()
    {
        $payload = 'invalid payload';

        $response = $this->postJson(route('webhook.handle'), $payload, [
            'Stripe-Signature' => 'invalid-signature',
        ]);

        $response->assertStatus(400);
        $response->assertJson(['error' => 'Invalid payload']);
    }

    /** @test */
    public function it_returns_error_for_invalid_signature()
    {
        $payload = json_encode([
            'type' => 'payment_intent.succeeded',
            'data' => [
                'object' => [
                    'id' => 'pi_12345',
                ],
            ],
        ]);

        $response = $this->postJson(route('webhook.handle'), $payload, [
            'Stripe-Signature' => 'invalid-signature',
        ]);

        $response->assertStatus(400);
        $response->assertJson(['error' => 'Invalid signature']);
    }

    protected function generateStripeSignature($payload)
    {
        $secret = env('STRIPE_WEBHOOK_SECRET');
        $timestamp = time();
        $signedPayload = $timestamp . '.' . $payload;
        $signature = hash_hmac('sha256', $signedPayload, $secret);

        return "t={$timestamp},v1={$signature}";
    }
}
