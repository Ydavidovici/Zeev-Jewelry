<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Order;
use App\Models\User;
use App\Models\Customer;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_orders()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        Order::factory()->count(3)->create();

        $response = $this->getJson('/api/orders');

        $response->assertStatus(200)
            ->assertJsonStructure([[]]); // Expect an array of orders
    }

    public function test_user_can_create_order()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $customer = Customer::factory()->create();

        $response = $this->postJson('/api/orders', [
            'customer_id' => $customer->id,
            'order_date' => now()->toDateString(),
            'total_amount' => 100,
            'is_guest' => false,
            'status' => 'Pending',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'customer_id', 'order_date', 'total_amount', 'is_guest', 'status']);
    }

    public function test_user_can_view_single_order()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $order = Order::factory()->create();

        $response = $this->getJson("/api/orders/{$order->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'customer_id', 'order_date', 'total_amount', 'is_guest', 'status']);
    }

    public function test_user_can_update_order()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $order = Order::factory()->create();
        $customer = Customer::factory()->create();

        $response = $this->putJson("/api/orders/{$order->id}", [
            'customer_id' => $customer->id,
            'order_date' => now()->toDateString(),
            'total_amount' => 200,
            'is_guest' => false,
            'status' => 'Completed',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'customer_id', 'order_date', 'total_amount', 'is_guest', 'status']);
    }

    public function test_user_can_delete_order()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $order = Order::factory()->create();

        $response = $this->deleteJson("/api/orders/{$order->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}
