<?php

namespace Tests\Feature\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    #[Test]
    public function it_can_view_all_orders()
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller'); // Assign the seller role
        $this->actingAs($seller, 'api');

        $customer = User::factory()->create();
        $customer->assignRole('customer');

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'seller_id' => $seller->id,
        ]);

        $response = $this->getJson(route('orders.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['*' => ['id', 'customer_id', 'order_date', 'total_amount']]);
    }
    #[Test]
    public function it_can_create_an_order()
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');
        $this->actingAs($seller, 'api');

        $customer = User::factory()->create();
        $customer->assignRole('customer');

        $orderData = [
            'customer_id' => $customer->id,
            'order_date' => now()->format('Y-m-d'),
            'total_amount' => 100.50,
            'is_guest' => false,
            'status' => 'processing',
        ];

        $response = $this->postJson(route('orders.store'), $orderData);

        $response->assertStatus(201)
            ->assertJsonFragment(['status' => 'processing']);

        $this->assertDatabaseHas('orders', ['total_amount' => 100.50, 'status' => 'processing']);
    }

    #[Test]
    public function it_can_show_an_order()
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');
        $this->actingAs($seller, 'api');

        $order = Order::factory()->create(['seller_id' => $seller->id]);

        $response = $this->getJson(route('orders.show', $order->id));

        $response->assertStatus(200)
            ->assertJson(['id' => $order->id]);
    }

    #[Test]
    public function it_can_update_an_order()
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');
        $this->actingAs($seller, 'api');

        $order = Order::factory()->create(['seller_id' => $seller->id]);

        $response = $this->putJson(route('orders.update', $order->id), ['status' => 'shipped']);

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => 'shipped']);

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'shipped']);
    }

    #[Test]
    public function it_can_delete_an_order()
    {
        $seller = User::factory()->create();
        $seller->assignRole('seller');
        $this->actingAs($seller, 'api');

        $order = Order::factory()->create(['seller_id' => $seller->id]);

        $response = $this->deleteJson(route('orders.destroy', $order->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}
