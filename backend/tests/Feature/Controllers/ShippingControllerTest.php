<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Shipping;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ShippingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShippingIndex()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        Shipping::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/shipping');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function testShippingStore()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $data = [
            'order_id' => 1,
            'seller_id' => 1,
            'shipping_type' => 'Standard',
            'shipping_cost' => 10,
            'shipping_status' => 'Pending',
            'tracking_number' => '1234567890',
            'shipping_address' => '123 Main St',
            'shipping_carrier' => 'FedEx',
            'recipient_name' => 'John Doe',
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/shipping', $data);

        $response->assertStatus(201)
            ->assertJson($data);
    }

    public function testShippingShow()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $shipping = Shipping::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/shipping/{$shipping->id}");

        $response->assertStatus(200)
            ->assertJson($shipping->toArray());
    }

    public function testShippingUpdate()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $shipping = Shipping::factory()->create();

        $data = [
            'shipping_status' => 'Shipped',
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/shipping/{$shipping->id}", $data);

        $response->assertStatus(200)
            ->assertJson($data);
    }

    public function testShippingDestroy()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $shipping = Shipping::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->deleteJson("/api/shipping/{$shipping->id}");

        $response->assertStatus(204);
    }
}
