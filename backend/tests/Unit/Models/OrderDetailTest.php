<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class OrderDetailTest extends TestCase
{
    use RefreshDatabase;

    // Seed roles before each test
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    // Method to seed roles for testing
    private function seedRoles()
    {
        if (Role::where('name', 'Seller')->doesntExist()) {
            Role::create(['name' => 'Seller', 'guard_name' => 'api']);
        }
        if (Role::where('name', 'customer')->doesntExist()) {
            Role::create(['name' => 'customer', 'guard_name' => 'api']);
        }
        if (Role::where('name', 'admin')->doesntExist()) {
            Role::create(['name' => 'admin', 'guard_name' => 'api']);
        }
    }

    public function test_order_detail_belongs_to_order()
    {
        $order = Order::factory()->create();
        $product = Product::factory()->create(); // Ensure a product is created
        $orderDetail = OrderDetail::factory()->create(['order_id' => $order->id, 'product_id' => $product->id]);

        $this->assertInstanceOf(Order::class, $orderDetail->order);
        $this->assertEquals($order->id, $orderDetail->order->id);
    }

    public function test_order_detail_belongs_to_product()
    {
        $product = Product::factory()->create();
        $orderDetail = OrderDetail::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Product::class, $orderDetail->product);
        $this->assertEquals($product->id, $orderDetail->product->id);
    }

    public function test_order_detail_has_quantity()
    {
        $orderDetail = OrderDetail::factory()->create(['quantity' => 3]);

        $this->assertEquals(3, $orderDetail->quantity);
    }

    public function test_order_detail_has_price()
    {
        $orderDetail = OrderDetail::factory()->create(['price' => 29.99]);

        $this->assertEquals(29.99, $orderDetail->price);
    }
}
