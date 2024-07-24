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

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and set them as the current authenticated user
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function user_can_view_orders_index()
    {
        $response = $this->get(route('orders.index'));

        $response->assertStatus(200);
        $response->assertViewIs('orders.index');
        $response->assertViewHas('orders');
    }

    /** @test */
    public function user_can_view_create_order_form()
    {
        $response = $this->get(route('orders.create'));

        $response->assertStatus(200);
        $response->assertViewIs('orders.create');
    }

    /** @test */
    public function user_can_create_order()
    {
        $customer = Customer::factory()->create();

        $data = [
            'customer_id' => $customer->id,
            'order_date' => now()->format('Y-m-d'),
            'total_amount' => 100.50,
            'is_guest' => false,
            'status' => 'Pending',
        ];

        $response = $this->post(route('orders.store'), $data);

        $response->assertRedirect(route('orders.index'));
        $this->assertDatabaseHas('orders', $data);
    }

    /** @test */
    public function user_can_view_edit_order_form()
    {
        $order = Order::factory()->create();

        $response = $this->get(route('orders.edit', $order));

        $response->assertStatus(200);
        $response->assertViewIs('orders.edit');
        $response->assertViewHas('order', $order);
    }

    /** @test */
    public function user_can_update_order()
    {
        $order = Order::factory()->create();

        $data = [
            'customer_id' => $order->customer_id,
            'order_date' => now()->format('Y-m-d'),
            'total_amount' => 200.75,
            'is_guest' => false,
            'status' => 'Completed',
        ];

        $response = $this->put(route('orders.update', $order), $data);

        $response->assertRedirect(route('orders.index'));
        $this->assertDatabaseHas('orders', array_merge(['id' => $order->id], $data));
    }

    /** @test */
    public function user_can_delete_order()
    {
        $order = Order::factory()->create();

        $response = $this->delete(route('orders.destroy', $order));

        $response->assertRedirect(route('orders.index'));
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}
