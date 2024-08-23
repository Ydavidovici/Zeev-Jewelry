<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCustomerIndex()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        Customer::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/customers');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'customers');
    }

    public function testCustomerStore()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $data = [
            'user_id' => $user->id,
            'address' => '123 Main St',
            'phone_number' => '1234567890',
            'email' => 'customer@example.com',
            'is_guest' => false,
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/customers', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('customers', $data);
    }

    public function testCustomerShow()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $customer = Customer::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/customers/{$customer->id}");

        $response->assertStatus(200)
            ->assertJson(['customer' => $customer->toArray()]);
    }

    public function testCustomerUpdate()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $customer = Customer::factory()->create();

        $data = [
            'address' => '456 Elm St',
            'phone_number' => '9876543210',
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/customers/{$customer->id}", $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('customers', $data);
    }

    public function testCustomerDestroy()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $customer = Customer::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->deleteJson("/api/customers/{$customer->id}");

        $response->assertStatus(204);
        $this->assertDeleted($customer);
    }
}
