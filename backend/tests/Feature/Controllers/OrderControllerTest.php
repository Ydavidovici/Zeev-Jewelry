<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testOrderIndex()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        Order::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/orders');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function testOrderStore()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $data = [
            'customer_id' => 1,
            'order_date' => now(),
            'total_amount' => 100,
            'is_guest' => false,
            'status' => 'Pending',
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/orders', $data);

        $response->assertStatus(201)
            ->assertJson($data);
    }

    public function testOrderShow()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $order = Order::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/orders/{$order->id}");

        $response->assertStatus(200)
            ->assertJson($order->toArray());
    }

    public function testOrderUpdate()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $order = Order::factory()->create();

        $data = [
            'status' => 'Shipped',
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/orders/{$order->id}", $data);

        $response->assertStatus(200)
            ->assertJson($data);
    }

    public function testOrderDestroy()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $order = Order::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->deleteJson("/api/orders/{$order->id}");

        $response->assertStatus(204);
    }
}
