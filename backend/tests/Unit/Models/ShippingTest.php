<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Shipping;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use PHPUnit\Framework\Attributes\Test;

class ShippingTest extends TestCase
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
        if (Role::where('name', 'seller')->doesntExist()) {
            Role::create(['name' => 'seller', 'guard_name' => 'api']);
        }
        if (Role::where('name', 'customer')->doesntExist()) {
            Role::create(['name' => 'customer', 'guard_name' => 'api']);
        }
        if (Role::where('name', 'admin')->doesntExist()) {
            Role::create(['name' => 'admin', 'guard_name' => 'api']);
        }
    }

    #[Test]
    public function shipping_belongs_to_order()
    {
        $order = Order::factory()->create();
        $shipping = Shipping::factory()->create(['order_id' => $order->id]);

        $this->assertInstanceOf(Order::class, $shipping->order);
        $this->assertEquals($order->id, $shipping->order->id);
    }

    #[Test]
    public function shipping_has_tracking_number()
    {
        $shipping = Shipping::factory()->create(['tracking_number' => '123456789']);

        $this->assertEquals('123456789', $shipping->tracking_number);
    }

    #[Test]
    public function shipping_has_address()
    {
        $shipping = Shipping::factory()->create(['shipping_address' => '123 Main St']);

        $this->assertEquals('123 Main St', $shipping->shipping_address);
    }

    #[Test]
    public function shipping_has_city()
    {
        $shipping = Shipping::factory()->create(['city' => 'New York']);

        $this->assertEquals('New York', $shipping->city);
    }

    #[Test]
    public function shipping_has_state()
    {
        $shipping = Shipping::factory()->create(['state' => 'NY']);

        $this->assertEquals('NY', $shipping->state);
    }

    #[Test]
    public function shipping_has_postal_code()
    {
        $shipping = Shipping::factory()->create(['postal_code' => '10001']);

        $this->assertEquals('10001', $shipping->postal_code);
    }

    #[Test]
    public function shipping_has_country()
    {
        $shipping = Shipping::factory()->create(['country' => 'USA']);

        $this->assertEquals('USA', $shipping->country);
    }

    #[Test]
    public function shipping_has_shipping_method()
    {
        $shipping = Shipping::factory()->create(['shipping_method' => 'FedEx']);

        $this->assertEquals('FedEx', $shipping->shipping_method);
    }

    #[Test]
    public function shipping_has_status()
    {
        $shipping = Shipping::factory()->create(['shipping_status' => 'shipped']);

        $this->assertEquals('shipped', $shipping->shipping_status);
    }

    #[Test]
    public function shipping_has_estimated_delivery_date()
    {
        $date = now();
        $shipping = Shipping::factory()->create(['estimated_delivery_date' => $date]);

        $this->assertEquals($date, $shipping->estimated_delivery_date);
    }

    #[Test]
    public function shipping_has_recipient_name()
    {
        $shipping = Shipping::factory()->create(['recipient_name' => 'John Doe']);

        $this->assertEquals('John Doe', $shipping->recipient_name);
    }
}
