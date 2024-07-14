<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Customer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_customer()
    {
        $user = User::factory()->create(); // Create a user first
        $customer = Customer::factory()->create([
            'user_id' => $user->id,
            'address' => '123 Main St',
            'phone_number' => '123-456-7890',
            'email' => 'customer@example.com',
            'is_guest' => false,
        ]);

        $this->assertDatabaseHas('customers', ['email' => 'customer@example.com']);
    }
}
