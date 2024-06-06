<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_customer()
    {
        $customer = Customer::factory()->create([
            'user_id' => 1,
            'address' => '123 Main St',
            'phone_number' => '123-456-7890',
            'email' => 'customer@example.com',
            'is_guest' => false,
        ]);

        $this->assertDatabaseHas('customers', ['email' => 'customer@example.com']);
    }
}
