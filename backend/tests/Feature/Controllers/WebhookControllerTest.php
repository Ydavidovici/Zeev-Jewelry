<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Illuminate\Support\Facades\Http;

class WebhookControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_handles_successful_payment_intent_webhook()
    {
        $order = Order::factory()->create([
            'payment_intent_id' => 'pi_test',
            'status' => 'pending',
        ]);

        $payment = Payment::factory()->create([
            'order_id' => $order->id,
            'payment_intent_id' => 'pi_test',
            'payment_status' => 'pending',
        ]);

        $payload = json_encode([
            'type' => 'payment_intent.succeeded',
            'data' => [
                'object' => [
                    'id' => 'pi_test',
                ],
            ],
        ]);

        $sigHeader = 't=' . time() . ',v1=' . hash_hmac('sha256', $payload, config('services.stripe.webhook_secret'));

        $response = $this->postJson('/api/stripe/webhook', [], [
            'Stripe-Signature' => $sigHeader,
            'Content-Type' => 'application/json',
        ]);

        $response->assertStatus(200);
        $order->refresh();
        $payment->refresh();

        $this->assertEquals('Paid', $order->status);
        $this->assertEquals('succeeded', $payment->payment_status);
    }

    /** @test */
    public function it_logs_unknown_event_type()
    {
        Log::shouldReceive('warning')
            ->once()
            ->with('Received unknown event type unknown_event');

        $payload = json_encode([
            'type' => 'unknown_event',
            'data' => [
                'object' => [
                    'id' => 'pi_test',
                ],
            ],
        ]);

        $sigHeader = 't=' . time() . ',v1=' . hash_hmac('sha256', $payload, config('services.stripe.webhook_secret'));

        $response = $this->postJson('/api/stripe/webhook', [], [
            'Stripe-Signature' => $sigHeader,
            'Content-Type' => 'application/json',
        ]);

        $response->assertStatus(200);
    }
}
