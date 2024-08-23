<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\OrderDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderDetailControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testOrderDetailIndex()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        OrderDetail::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/order_details');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function testOrderDetailStore()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $data = [
            'order_id' => 1,
            'product_id' => 1,
            'quantity' => 2,
            'price' => 50,
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/order_details', $data);

        $response->assertStatus(201)
            ->assertJson($data);
    }

    public function testOrderDetailShow()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $orderDetail = OrderDetail::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/order_details/{$orderDetail->id}");

        $response->assertStatus(200)
            ->assertJson($orderDetail->toArray());
    }

    public function testOrderDetailUpdate()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $orderDetail = OrderDetail::factory()->create();

        $data = [
            'quantity' => 3,
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/order_details/{$orderDetail->id}", $data);

        $response->assertStatus(200)
            ->assertJson($data);
    }

    public function testOrderDetailDestroy()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $orderDetail = OrderDetail::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->deleteJson("/api/order_details/{$orderDetail->id}");

        $response->assertStatus(204);
    }
}
