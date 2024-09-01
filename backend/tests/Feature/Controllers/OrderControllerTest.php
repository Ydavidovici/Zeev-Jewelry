<?php

namespace Tests\Feature\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create(), 'api');
    }

    /** @test */
    public function it_can_view_all_orders()
    {
        Gate::define('viewAny', function ($user) {
            return true;
        });

        $response = $this->getJson(route('orders.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['*' => ['id', 'customer_id', 'order_date', 'total_amount']]);
    }

    /** @test */
    public function it_can_create_an_order()
    {
        Gate::define('create', function ($user) {
            return true;
        });

        $orderData = [
            'customer_id' => 1,
            'order_date' => now()->toDateString(),
            'total_amount' => 100.00,
            'is_guest' => false,
            'status' => 'Pending',
            'payment_intent_id' => 'test_intent_id',
        ];

        $response = $this->postJson(route('orders.store'), $orderData);

        $response->assertStatus(201)
            ->assertJsonFragment(['status' => 'Pending']);

        $this->assertDatabaseHas('orders', ['customer_id' => 1, 'total_amount' => 100.00]);
    }

    /** @test */
    public function it_can_show_an_order()
    {
        Gate::define('view', function ($user, $order) {
            return true;
        });

        $order = Order::factory()->create();

        $response = $this->getJson(route('orders.show', $order->id));

        $response->assertStatus(200)
            ->assertJson(['id' => $order->id]);
    }

    /** @test */
    public function it_can_update_an_order()
    {
        Gate::define('update', function ($user, $order) {
            return true;
        });

        $order = Order::factory()->create();

        $response = $this->putJson(route('orders.update', $order->id), ['status' => 'Completed']);

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => 'Completed']);

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'Completed']);
    }

    /** @test */
    public function it_can_delete_an_order()
    {
        Gate::define('delete', function ($user, $order) {
            return true;
        });

        $order = Order::factory()->create();

        $response = $this->deleteJson(route('orders.destroy', $order->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}
