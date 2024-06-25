<?php

namespace Tests\Unit\Models;

use App\Models\Order;
use App\Models\Shipping;
use backend\tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShippingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_shipping()
    {
        $order = Order::factory()->create(); // Create an order first
        $shipping = Shipping::factory()->create([
            'order_id' => $order->id,
            'shipping_type' => 'Standard',
            'shipping_cost' => 10.00,
            'shipping_status' => 'pending',
        ]);

        $this->assertDatabaseHas('shipping', ['shipping_type' => 'Standard']);
    }
}
