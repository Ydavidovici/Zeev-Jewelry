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
        if (Role::where('name', 'Seller')->doesntExist()) {
            Role::create(['name' => 'Seller', 'guard_name' => 'api']);
        }
        if (Role::where('name', 'customer')->doesntExist()) {
            Role::create(['name' => 'customer', 'guard_name' => 'api']);
        }
        if (Role::where('name', 'admin')->doesntExist()) {
            Role::create(['name' => 'admin', 'guard_name' => 'api']);
        }
    }

    // Test the relationship between Order and Payment (1:n)
    public function test_order_has_many_payments()
    {
        $order = Order::factory()->create();
        $payment = Payment::factory()->create(['order_id' => $order->id]);

        $this->assertTrue($order->payments->contains($payment));
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $order->payments);
    }

    // Test the relationship between Order and User (customer)
    public function test_order_belongs_to_customer()
    {
        $user = User::factory()->create(); // Use User instead of Customer
        $order = Order::factory()->create(['customer_id' => $user->id]);

        $this->assertInstanceOf(User::class, $order->customer); // Assert that the customer is a User instance
        $this->assertEquals($user->id, $order->customer->id); // Assert that the customer ID matches the user's ID
    }

    // Test the relationship between Order and Seller (seller_id field)
    public function test_order_belongs_to_seller()
    {
        $seller = User::factory()->create();
        $seller->assignRole('Seller'); // Assign the 'Seller' role to the user
        $order = Order::factory()->create(['seller_id' => $seller->id]);

        $this->assertInstanceOf(User::class, $order->seller); // Assert that the seller is a User instance
        $this->assertEquals($seller->id, $order->seller->id); // Assert that the seller ID matches the user's ID
    }
}
