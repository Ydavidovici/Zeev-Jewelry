<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Customer;
use App\Models\User;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function customer_belongs_to_user()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $customer->user);
        $this->assertEquals($user->id, $customer->user->id);
    }

    #[Test]
    public function customer_has_many_reviews()
    {
        $customer = Customer::factory()->create();
        $product = Product::factory()->create(); // Ensure product is created
        $review = Review::factory()->create([
            'product_id' => $product->id, // Ensure the product ID is assigned correctly
            'customer_id' => $customer->id, // Ensure customer ID is assigned correctly
            'review_date' => now() // Add review date
        ]);

        $this->assertTrue($customer->reviews->contains($review));
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $customer->reviews);
    }
}
