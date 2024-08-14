<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Customer;
use App\Models\User;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_customer()
    {
        $user = User::factory()->create();
        $data = [
            'user_id' => $user->id,
            'address' => '123 Main St',
            'phone_number' => '555-555-5555',
            'email' => 'customer@example.com',
            'is_guest' => false,
        ];

        $response = $this->actingAs($user)->post(route('customers.store'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('customers', ['email' => 'customer@example.com']);
    }

    public function test_read_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs($customer->user)->get(route('customers.show', $customer->id));

        $response->assertStatus(200);
        $response->assertJson($customer->toArray());
    }

    public function test_update_customer()
    {
        $customer = Customer::factory()->create();
        $data = [
            'address' => '456 Another St',
            'phone_number' => '666-666-6666',
        ];

        $response = $this->actingAs($customer->user)->put(route('customers.update', $customer->id), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('customers', ['id' => $customer->id, 'address' => '456 Another St']);
    }

    public function test_delete_customer()
    {
        $customer = Customer::factory()->create();
        $customerId = $customer->id;

        $response = $this->actingAs($customer->user)->delete(route('customers.destroy', $customerId));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('customers', ['id' => $customerId]);
    }
}
