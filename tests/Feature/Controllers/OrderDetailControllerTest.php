<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\OrderDetail;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;

class OrderDetailControllerTest extends TestCase
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
    public function user_can_view_order_details_index()
    {
        $response = $this->get(route('order-details.index'));

        $response->assertStatus(200);
        $response->assertViewIs('order_details.index');
        $response->assertViewHas('orderDetails');
    }

    /** @test */
    public function user_can_view_create_order_detail_form()
    {
        $response = $this->get(route('order-details.create'));

        $response->assertStatus(200);
        $response->assertViewIs('order_details.create');
    }

    /** @test */
    public function user_can_create_order_detail()
    {
        $order = Order::factory()->create();
        $product = Product::factory()->create();

        $data = [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 100.50,
        ];

        $response = $this->post(route('order-details.store'), $data);

        $response->assertRedirect(route('order-details.index'));
        $this->assertDatabaseHas('order_details', $data);
    }

    /** @test */
    public function user_can_view_edit_order_detail_form()
    {
        $orderDetail = OrderDetail::factory()->create();

        $response = $this->get(route('order-details.edit', $orderDetail));

        $response->assertStatus(200);
        $response->assertViewIs('order_details.edit');
        $response->assertViewHas('orderDetail', $orderDetail);
    }

    /** @test */
    public function user_can_update_order_detail()
    {
        $orderDetail = OrderDetail::factory()->create();

        $data = [
            'order_id' => $orderDetail->order_id,
            'product_id' => $orderDetail->product_id,
            'quantity' => 5,
            'price' => 150.75,
        ];

        $response = $this->put(route('order-details.update', $orderDetail), $data);

        $response->assertRedirect(route('order-details.index'));
        $this->assertDatabaseHas('order_details', array_merge(['id' => $orderDetail->id], $data));
    }

    /** @test */
    public function user_can_delete_order_detail()
    {
        $orderDetail = OrderDetail::factory()->create();

        $response = $this->delete(route('order-details.destroy', $orderDetail));

        $response->assertRedirect(route('order-details.index'));
        $this->assertDatabaseMissing('order_details', ['id' => $orderDetail->id]);
    }
}
