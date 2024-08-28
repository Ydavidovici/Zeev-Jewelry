<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User; // Use User instead of Customer
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class OrderTest extends TestCase
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

    public function test_order_has_many_payments()
    {
        $order = Order::factory()->create();
        $payment = Payment::factory()->create(['order_id' => $order->id]);

        $this->assertTrue($order->payments->contains($payment));
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $order->payments);
    }

    public function test_order_belongs_to_customer()
    {
        $user = User::factory()->create(); // Use User instead of Customer
        $order = Order::factory()->create(['customer_id' => $user->id]);

        $this->assertInstanceOf(User::class, $order->customer); // Update assertion to use User class
        $this->assertEquals($user->id, $order->customer->id); // Update to use User instance
    }
}
