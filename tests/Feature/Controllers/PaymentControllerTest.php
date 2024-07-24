<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Stripe;
use Tests\TestCase;
use App\Models\Payment;
use App\Models\User;
use App\Models\Order;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and set them as the current authenticated user
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function user_can_view_payments_index()
    {
        $response = $this->get(route('payments.index'));

        $response->assertStatus(200);
        $response->assertViewIs('payments.index');
        $response->assertViewHas('payments');
    }

    /** @test */
    public function user_can_view_create_payment_form()
    {
        $response = $this->get(route('payments.create'));

        $response->assertStatus(200);
        $response->assertViewIs('payments.create');
    }

    /** @test */
    public function user_can_create_payment_intent()
    {
        Stripe::fake();

        $order = Order::factory()->create();

        $data = [
            'order_id' => $order->id,
            'amount' => 100,
        ];

        $response = $this->post(route('payments.store'), $data);

        $response->assertStatus(200);
        $response->assertJsonStructure(['clientSecret']);
    }

    /** @test */
    public function user_can_confirm_payment_intent()
    {
        Stripe::fake();

        $order = Order::factory()->create(['status' => 'Pending']);
        $paymentIntent = Stripe::paymentIntents()->create([
            'amount' => 10000,
            'currency' => 'usd',
            'payment_method_types' => ['card'],
        ]);

        $data = [
            'payment_intent_id' => $paymentIntent->id,
            'order_id' => $order->id,
        ];

        $response = $this->post(route('payments.confirm'), $data);

        $response->assertRedirect(route('checkout.success'));
        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'payment_type' => 'stripe',
            'payment_status' => 'succeeded',
            'amount' => 100,
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'Paid',
        ]);
    }

    /** @test */
    public function user_can_view_payment_details()
    {
        $payment = Payment::factory()->create();

        $response = $this->get(route('payments.show', $payment));

        $response->assertStatus(200);
        $response->assertViewIs('payments.show');
        $response->assertViewHas('payment', $payment);
    }

    /** @test */
    public function user_can_update_payment()
    {
        $payment = Payment::factory()->create();

        $data = [
            'order_id' => $payment->order_id,
            'payment_status' => 'completed',
        ];

        $response = $this->put(route('payments.update', $payment), $data);

        $response->assertRedirect(route('payments.index'));
        $this->assertDatabaseHas('payments', array_merge(['id' => $payment->id], $data));
    }

    /** @test */
    public function user_can_delete_payment()
    {
        $payment = Payment::factory()->create();

        $response = $this->delete(route('payments.destroy', $payment));

        $response->assertRedirect(route('payments.index'));
        $this->assertDatabaseMissing('payments', ['id' => $payment->id]);
    }
}
