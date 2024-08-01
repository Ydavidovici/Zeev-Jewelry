<?php

namespace Tests\Database;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use PHPUnit\Framework\Attributes\Test;

class FactoriesTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_user()
    {
        $user = User::factory()->create();
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    #[Test]
    public function it_can_create_product()
    {
        $product = Product::factory()->create();
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    #[Test]
    public function it_can_create_order()
    {
        $order = Order::factory()->create();
        $this->assertDatabaseHas('orders', ['id' => $order->id]);
    }
}
