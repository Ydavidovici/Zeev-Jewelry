<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_an_order()
    {
        $order = Order::factory()->create([
            'customer_id' => 1,
            'order_date' => now(),
            'total_amount' => 100.00,
            'is_guest' => false,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('orders', ['total_amount' => 100.00]);
    }
}
