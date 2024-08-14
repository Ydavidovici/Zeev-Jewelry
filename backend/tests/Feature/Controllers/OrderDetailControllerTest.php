<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class OrderDetailControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_order_details()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        OrderDetail::factory()->count(3)->create();

        $response = $this->getJson('/api/order_details');

        $response->assertStatus(200)
            ->assertJsonStructure([[]]); // Expect an array of order details
    }

    public function test_user_can_create_order_detail()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $order = Order::factory()->create();
        $product = Product::factory()->create();

        $response = $this->postJson('/api/order_details', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 50,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'order_id', 'product_id', 'quantity', 'price']);
    }

    public function test_user_can_view_single_order_detail()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $orderDetail = OrderDetail::factory()->create();

        $response = $this->getJson("/api/order_details/{$orderDetail->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'order_id', 'product_id', 'quantity', 'price']);
    }

    public function test_user_can_update_order_detail()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $orderDetail = OrderDetail::factory()->create();

        $response = $this->putJson("/api/order_details/{$orderDetail->id}", [
            'quantity' => 3,
            'price' => 60,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'order_id', 'product_id', 'quantity', 'price']);
    }

    public function test_user_can_delete_order_detail()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $orderDetail = OrderDetail::factory()->create();

        $response = $this->deleteJson("/api/order_details/{$orderDetail->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('order_details', ['id' => $orderDetail->id]);
    }
}
