<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Request;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Stripe\Stripe;
use Illuminate\Support\Facades\Log;

class WebhookControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testWebhookHandlePaymentSucceeded()
    {
        // Mock the Stripe Webhook constructEvent method to simulate Stripe webhook event
        $this->mock(Webhook::class, function ($mock) {
            $mock->shouldReceive('constructEvent')
                ->andReturn((object) [
                    'type' => 'payment_intent.succeeded',
                    'data' => (object) [
                        'object' => (object) [
                            'id' => 'pi_1GqIC8I7cO5EaPm4u0d5C4K6',
                            'status' => 'succeeded',
                        ],
                    ],
                ]);
        });

        $payload = json_encode([
            'type' => 'payment_intent.succeeded',
            'data' => [
                'object' => [
                    'id' => 'pi_1GqIC8I7cO5EaPm4u0d5C4K6',
                    'status' => 'succeeded',
                ],
            ],
        ]);

        $response = $this->postJson('/api/webhook', [], ['Stripe-Signature' => 'some-signature']);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);
    }

    public function testWebhookHandleInvalidSignature()
    {
        // Mock the Stripe Webhook constructEvent method to throw a SignatureVerificationException
        $this->mock(Webhook::class, function ($mock) {
            $mock->shouldReceive('constructEvent')
                ->andThrow(SignatureVerificationException::class);
        });

        $payload = json_encode([
            'type' => 'payment_intent.succeeded',
            'data' => [
                'object' => [
                    'id' => 'pi_1GqIC8I7cO5EaPm4u0d5C4K6',
                    'status' => 'succeeded',
                ],
            ],
        ]);

        $response = $this->postJson('/api/webhook', [], ['Stripe-Signature' => 'some-invalid-signature']);

        $response->assertStatus(400)
            ->assertJson(['error' => 'Invalid signature']);
    }
}
