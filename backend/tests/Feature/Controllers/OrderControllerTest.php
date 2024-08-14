<?php

namespace Tests\Feature\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Mail\ShippingConfirmationMail;
use Tests\TestCase;
use App\Models\Order;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_all_orders()
    {
        $this->actingAs(User::factory()->create());

        $order = Order::factory()->create();

        $response = $this->getJson('/api/orders');

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $order->id]);
    }

    public function test_user_can_create_an_order()
    {
        Mail::fake();

        $this->actingAs(User::factory()->create());

        $orderData = [
            'customer_id' => Customer::factory()->create()->id,
            'order_date' => now()->toDateString(),
            'total_amount' => 150.00,
            'is_guest' => false,
            'status' => 'pending',
            'payment_intent_id' => 'pi_123456789',
        ];

        $response = $this->postJson('/api/orders', $orderData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'payment_intent_id' => 'pi_123456789',
            ]);

        $this->assertDatabaseHas('orders', $orderData);

        $order = Order::first();

        Mail::assertSent(OrderConfirmationMail::class, function ($mail) use ($order) {
            return $mail->hasTo($order->customer->email) && $mail->order->is($order);
        });
    }

    public function test_user_can_view_a_single_order()
    {
        $this->actingAs(User::factory()->create());

        $order = Order::factory()->create();

        $response = $this->getJson('/api/orders/' . $order->id);

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $order->id]);
    }

    public function test_user_can_update_an_order()
    {
        Mail::fake();

        $this->actingAs(User::factory()->create());

        $order = Order::factory()->create();

        $updateData = [
            'customer_id' => Customer::factory()->create()->id,
            'order_date' => now()->toDateString(),
            'total_amount' => 200.00,
            'is_guest' => true,
            'status' => 'shipped', // We change status to shipped here
            'payment_intent_id' => 'pi_987654321',
        ];

        $response = $this->putJson('/api/orders/' . $order->id, $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'payment_intent_id' => 'pi_987654321',
            ]);

        $this->assertDatabaseHas('orders', $updateData);

        Mail::assertSent(ShippingConfirmationMail::class, function ($mail) use ($order) {
            return $mail->hasTo($order->customer->email) && $mail->order->is($order);
        });
    }

    public function test_user_can_delete_an_order()
    {
        $this->actingAs(User::factory()->create());

        $order = Order::factory()->create();

        $response = $this->deleteJson('/api/orders/' . $order->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}
