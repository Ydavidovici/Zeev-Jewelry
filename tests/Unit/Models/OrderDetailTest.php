<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\OrderDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderDetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_an_order_detail()
    {
        $orderDetail = OrderDetail::factory()->create([
            'order_id' => 1,
            'product_id' => 1,
            'quantity' => 2,
            'price' => 50.00,
        ]);

        $this->assertDatabaseHas('order_details', ['quantity' => 2]);
    }
}
