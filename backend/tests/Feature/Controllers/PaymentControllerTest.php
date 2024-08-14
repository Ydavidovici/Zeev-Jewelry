<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Payment;
use App\Models\Order;
use App\Models\User;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_payments()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        Payment::factory()->count(3)->create();

        $response = $this->getJson('/api/payments');

        $response->assertStatus(200)
            ->assertJsonStructure([[]]); // Expect an array of payments
    }

    public function test_user_can_create_payment_intent()
    {
        Stripe::fake();
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $order = Order::factory()->create();

        $response = $this->postJson('/api/payments', [
            'order_id' => $order->id,
            'amount' => 100,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['clientSecret']);
    }

    public function test_user_can_confirm_payment()
    {
        Stripe::fake();
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $order = Order::factory()->create(['payment_intent_id' => 'test_intent']);

        $response = $this->postJson('/api/payments/confirm', [
            'payment_intent_id' => 'test_intent',
            'order_id' => $order->id,
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Payment successful.']);
    }

    public function test_user_can_view_single_payment()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $payment = Payment::factory()->create();

        $response = $this->getJson("/api/payments/{$payment->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'order_id', 'payment_type', 'payment_status', 'amount']);
    }

    public function test_user_can_update_payment()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $payment = Payment::factory()->create();

        $response = $this->putJson("/api/payments/{$payment->id}", [
            'payment_status' => 'Failed',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'order_id', 'payment_type', 'payment_status', 'amount']);
    }

    public function test_user_can_delete_payment()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $payment = Payment::factory()->create();

        $response = $this->deleteJson("/api/payments/{$payment->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('payments', ['id' => $payment->id]);
    }
}
