<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Shipping;

class ShippingTest extends TestCase
{
    public function test_shipping_has_order_id()
    {
        $shipping = new Shipping(['order_id' => 1]);

        $this->assertEquals(1, $shipping->order_id);
    }

    public function test_shipping_has_address()
    {
        $shipping = new Shipping(['address' => '123 Main St']);

        $this->assertEquals('123 Main St', $shipping->address);
    }

    public function test_shipping_has_city()
    {
        $shipping = new Shipping(['city' => 'New York']);

        $this->assertEquals('New York', $shipping->city);
    }

    public function test_shipping_has_state()
    {
        $shipping = new Shipping(['state' => 'NY']);

        $this->assertEquals('NY', $shipping->state);
    }

    public function test_shipping_has_postal_code()
    {
        $shipping = new Shipping(['postal_code' => '10001']);

        $this->assertEquals('10001', $shipping->postal_code);
    }

    public function test_shipping_has_country()
    {
        $shipping = new Shipping(['country' => 'USA']);

        $this->assertEquals('USA', $shipping->country);
    }

    public function test_shipping_has_shipping_method()
    {
        $shipping = new Shipping(['shipping_method' => 'FedEx']);

        $this->assertEquals('FedEx', $shipping->shipping_method);
    }

    public function test_shipping_has_tracking_number()
    {
        $shipping = new Shipping(['tracking_number' => '123456789']);

        $this->assertEquals('123456789', $shipping->tracking_number);
    }

    public function test_shipping_has_status()
    {
        $shipping = new Shipping(['status' => 'shipped']);

        $this->assertEquals('shipped', $shipping->status);
    }

    public function test_shipping_belongs_to_order()
    {
        $shipping = new Shipping();
        $relation = $shipping->order();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $relation);
        $this->assertEquals('order_id', $relation->getForeignKeyName());
    }
}
