<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCheckoutIndex()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/checkout');

        $response->assertStatus(200)
            ->assertJsonStructure(['cart']);
    }

    public function testCheckoutStore()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $data = [
            'address' => '123 Main St',
            'city' => 'New York',
            'postal_code' => '10001',
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/checkout', $data);

        $response->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    public function testCheckoutSuccess()
    {
        $response = $this->getJson('/api/checkout/success');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Order completed successfully.']);
    }

    public function testCheckoutFailure()
    {
        $response = $this->getJson('/api/checkout/failure');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Order failed.']);
    }
}
