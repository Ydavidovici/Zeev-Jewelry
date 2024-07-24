<?php

namespace Tests\Unit\Factories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;

class FactoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_user()
    {
        $user = User::factory()->create();
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    /** @test */
    public function it_can_create_product()
    {
        $product = Product::factory()->create();
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    /** @test */
    public function it_can_create_order()
    {
        $order = Order::factory()->create();
        $this->assertDatabaseHas('orders', ['id' => $order->id]);
    }
}
