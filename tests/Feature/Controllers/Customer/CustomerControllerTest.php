<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Customer;
use App\Models\User;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an authenticated user
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function user_can_view_customers_index()
    {
        $response = $this->get(route('customers.index'));

        $response->assertStatus(200);
        $response->assertViewIs('customers.index');
        $response->assertViewHas('customers');
    }

    /** @test */
    public function user_can_view_create_customer_form()
    {
        $response = $this->get(route('customers.create'));

        $response->assertStatus(200);
        $response->assertViewIs('customers.create');
    }

    /** @test */
    public function user_can_create_customer()
    {
        $data = [
            'user_id' => $this->user->id,
            'address' => '123 Test Street',
            'phone_number' => '1234567890',
            'email' => 'test@example.com',
            'is_guest' => false,
        ];

        $response = $this->post(route('customers.store'), $data);

        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseHas('customers', $data);
    }

    /** @test */
    public function user_can_view_edit_customer_form()
    {
        $customer = Customer::factory()->create();

        $response = $this->get(route('customers.edit', $customer));

        $response->assertStatus(200);
        $response->assertViewIs('customers.edit');
        $response->assertViewHas('customer', $customer);
    }

    /** @test */
    public function user_can_update_customer()
    {
        $customer = Customer::factory()->create();

        $data = [
            'user_id' => $customer->user_id,
            'address' => 'Updated Address',
            'phone_number' => '0987654321',
            'email' => 'updated@example.com',
            'is_guest' => false,
        ];

        $response = $this->put(route('customers.update', $customer), $data);

        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseHas('customers', array_merge(['id' => $customer->id], $data));
    }

    /** @test */
    public function user_can_delete_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->delete(route('customers.destroy', $customer));

        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }
}
