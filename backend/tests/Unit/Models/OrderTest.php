<?php

namespace Tests\Unit\Models;

use App\Models\Customer;
use App\Models\Order;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_an_order()
    {
        $customer = Customer::factory()->create(); // Create a customer first
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'order_date' => now(),
            'total_amount' => 100.00,
            'is_guest' => false,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('orders', ['total_amount' => 100.00]);
    }
}
