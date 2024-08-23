<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testPaymentIndex()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        Payment::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/payments');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function testPaymentStore()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $data = [
            'order_id' => 1,
            'amount' => 100,
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/payments', $data);

        $response->assertStatus(201)
            ->assertJsonStructure(['clientSecret']);
    }

    public function testPaymentConfirm()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $data = [
            'payment_intent_id' => 'pi_1GqIC8I7cO5EaPm4u0d5C4K6',
            'order_id' => 1,
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/payments/confirm', $data);

        $response->assertStatus(200)
            ->assertJsonStructure(['message']);
    }

    public function testPaymentShow()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $payment = Payment::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/payments/{$payment->id}");

        $response->assertStatus(200)
            ->assertJson($payment->toArray());
    }

    public function testPaymentUpdate()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $payment = Payment::factory()->create();

        $data = [
            'payment_status' => 'succeeded',
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/payments/{$payment->id}", $data);

        $response->assertStatus(200)
            ->assertJson($data);
    }

    public function testPaymentDestroy()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $payment = Payment::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->deleteJson("/api/payments/{$payment->id}");

        $response->assertStatus(204);
    }
}
