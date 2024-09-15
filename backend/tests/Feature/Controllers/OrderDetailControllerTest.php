<?php

namespace Tests\Feature\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderDetailControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    private function seedRoles()
    {
        if (!\Spatie\Permission\Models\Role::where('name', 'admin')->exists()) {
            \Spatie\Permission\Models\Role::create(['name' => 'admin', 'guard_name' => 'api']);
        }
    }

    #[Test]
    public function test_it_can_view_all_order_details()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin, 'api');

        OrderDetail::factory()->count(3)->create();

        $response = $this->getJson(route('order_details.index'));

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    #[Test]
    public function test_it_can_create_an_order_detail()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin, 'api');

        $order = Order::factory()->create();
        $product = Product::factory()->create();

        $orderDetailData = [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 29.99,
        ];

        $response = $this->postJson(route('order_details.store'), $orderDetailData);

        $response->assertStatus(201)
            ->assertJsonFragment(['price' => 29.99]);

        $this->assertDatabaseHas('order_details', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 29.99,
        ]);
    }

    #[Test]
    public function test_it_can_show_an_order_detail()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin, 'api');

        $orderDetail = OrderDetail::factory()->create();

        $response = $this->getJson(route('order_details.show', $orderDetail->id));

        $response->assertStatus(200)
            ->assertJson(['id' => $orderDetail->id]);
    }

    #[Test]
    public function test_it_can_update_an_order_detail()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin, 'api');

        $orderDetail = OrderDetail::factory()->create();

        $updatedData = [
            'order_id' => $orderDetail->order_id,
            'product_id' => $orderDetail->product_id,
            'quantity' => 5,
            'price' => 49.99,
        ];

        $response = $this->putJson(route('order_details.update', $orderDetail->id), $updatedData);

        $response->assertStatus(200)
            ->assertJsonFragment(['quantity' => 5]);

        $this->assertDatabaseHas('order_details', [
            'id' => $orderDetail->id,
            'quantity' => 5,
            'price' => 49.99,
        ]);
    }

    #[Test]
    public function test_it_can_delete_an_order_detail()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin, 'api');

        $orderDetail = OrderDetail::factory()->create();

        $response = $this->deleteJson(route('order_details.destroy', $orderDetail->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('order_details', ['id' => $orderDetail->id]);
    }
}
