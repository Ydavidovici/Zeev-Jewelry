<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_customers()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        Customer::factory()->count(3)->create();

        $response = $this->getJson('/api/customers');

        $response->assertStatus(200)
            ->assertJsonStructure(['customers' => [['id', 'user_id', 'address', 'phone_number', 'email', 'is_guest']]]);
    }

    public function test_user_can_create_customer()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/customers', [
            'user_id' => $user->id,
            'address' => '123 Main St',
            'phone_number' => '123-456-7890',
            'email' => 'customer@example.com',
            'is_guest' => false,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['customer' => ['id', 'user_id', 'address', 'phone_number', 'email', 'is_guest']]);
    }

    public function test_user_can_view_single_customer()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $customer = Customer::factory()->create();

        $response = $this->getJson("/api/customers/{$customer->id}");

        $response->assertStatus(200)
            ->assertJson(['customer' => ['id' => $customer->id, 'user_id' => $customer->user_id]]);
    }

    public function test_user_can_update_customer()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $customer = Customer::factory()->create();

        $response = $this->putJson("/api/customers/{$customer->id}", [
            'user_id' => $user->id,
            'address' => '456 Elm St',
            'phone_number' => '987-654-3210',
            'email' => 'updated@example.com',
            'is_guest' => true,
        ]);

        $response->assertStatus(200)
            ->assertJson(['customer' => ['id' => $customer->id, 'address' => '456 Elm St']]);
    }

    public function test_user_can_delete_customer()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $customer = Customer::factory()->create();

        $response = $this->deleteJson("/api/customers/{$customer->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }
}
