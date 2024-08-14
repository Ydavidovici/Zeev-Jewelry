<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Customer;

class CustomerTest extends TestCase
{
    public function test_customer_has_user_id()
    {
        $customer = new Customer(['user_id' => 1]);

        $this->assertEquals(1, $customer->user_id);
    }

    public function test_customer_has_address()
    {
        $customer = new Customer(['address' => '123 Main St']);

        $this->assertEquals('123 Main St', $customer->address);
    }

    public function test_customer_has_phone_number()
    {
        $customer = new Customer(['phone_number' => '555-555-5555']);

        $this->assertEquals('555-555-5555', $customer->phone_number);
    }

    public function test_customer_has_email()
    {
        $customer = new Customer(['email' => 'customer@example.com']);

        $this->assertEquals('customer@example.com', $customer->email);
    }

    public function test_customer_is_guest()
    {
        $customer = new Customer(['is_guest' => true]);

        $this->assertTrue($customer->is_guest);
    }

    public function test_customer_belongs_to_user()
    {
        $customer = new Customer();
        $relation = $customer->user();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $relation);
        $this->assertEquals('user_id', $relation->getForeignKeyName());
    }

    public function test_customer_has_many_reviews()
    {
        $customer = new Customer();
        $relation = $customer->reviews();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $relation);
        $this->assertEquals('customer_id', $relation->getForeignKeyName());
    }
}
