<?php

namespace Tests\Feature\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Stripe\Webhook;
use Tests\TestCase;

class WebhookControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        Log::spy();
    }

    /** @test */
    public function it_can_handle_a_successful_payment_intent()
    {
        // Create a dummy order and payment in the database
        $order = Order::factory()->create(['payment_intent_id' => 'pi_123456789']);
        $payment = Payment::factory()->create(['payment_intent_id' => 'pi_123456789', 'payment_status' => 'pending']);

        $payload = json_encode([
            'type' => 'payment_intent.succeeded',
            'data' => [
                'object' => [
                    'id' => 'pi_123456789',
                    'status' => 'succeeded',
                ],
            ],
        ]);

        $sigHeader = 't=' . time() . ',v1=' . hash_hmac('sha256', $payload, env('STRIPE_WEBHOOK_SECRET'));

        $response = $this->withHeaders(['Stripe-Signature' => $sigHeader])
            ->postJson(route('webhook.handle'), [], ['Content-Type' => 'application/json'], $payload);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'Paid']);
        $this->assertDatabaseHas('payments', ['id' => $payment->id, 'payment_status' => 'succeeded']);

        Log::shouldHaveReceived('info')->with('PaymentIntent was successful!');
    }

    /** @test */
    public function it_returns_400_for_invalid_payload()
    {
        $payload = 'invalid_payload';
        $sigHeader = 't=' . time() . ',v1=' . hash_hmac('sha256', $payload, env('STRIPE_WEBHOOK_SECRET'));

        $response = $this->withHeaders(['Stripe-Signature' => $sigHeader])
            ->postJson(route('webhook.handle'), [], ['Content-Type' => 'application/json'], $payload);

        $response->assertStatus(400)
            ->assertJson(['error' => 'Invalid payload']);
    }

    /** @test */
    public function it_returns_400_for_invalid_signature()
    {
        $payload = json_encode([
            'type' => 'payment_intent.succeeded',
            'data' => [
                'object' => [
                    'id' => 'pi_123456789',
                    'status' => 'succeeded',
                ],
            ],
        ]);

        $sigHeader = 't=' . time() . ',v1=' . hash_hmac('sha256', 'wrong_payload', env('STRIPE_WEBHOOK_SECRET'));

        $response = $this->withHeaders(['Stripe-Signature' => $sigHeader])
            ->postJson(route('webhook.handle'), [], ['Content-Type' => 'application/json'], $payload);

        $response->assertStatus(400)
            ->assertJson(['error' => 'Invalid signature']);
    }

    /** @test */
    public function it_logs_warning_for_unknown_event_type()
    {
        $payload = json_encode([
            'type' => 'unknown.event',
            'data' => [
                'object' => [],
            ],
        ]);

        $sigHeader = 't=' . time() . ',v1=' . hash_hmac('sha256', $payload, env('STRIPE_WEBHOOK_SECRET'));

        $response = $this->withHeaders(['Stripe-Signature' => $sigHeader])
            ->postJson(route('webhook.handle'), [], ['Content-Type' => 'application/json'], $payload);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        Log::shouldHaveReceived('warning')->with('Received unknown event type unknown.event');
    }
}
