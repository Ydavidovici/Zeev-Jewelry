<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Shipping;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShippingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_shipping()
    {
        $shipping = Shipping::factory()->create([
            'order_id' => 1,
            'shipping_type' => 'Standard',
            'shipping_cost' => 10.00,
            'shipping_status' => 'pending',
        ]);

        $this->assertDatabaseHas('shippings', ['shipping_type' => 'Standard']);
    }
}
