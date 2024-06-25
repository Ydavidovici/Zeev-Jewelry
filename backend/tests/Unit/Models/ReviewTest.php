<?php

namespace Tests\Unit\Models;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Review;
use backend\tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_review()
    {
        $product = Product::factory()->create(); // Create a product first
        $customer = Customer::factory()->create(); // Create a customer first
        $review = Review::factory()->create([
            'product_id' => $product->id,
            'customer_id' => $customer->id,
            'review_text' => 'Great product!',
            'rating' => 5,
        ]);

        $this->assertDatabaseHas('reviews', ['review_text' => 'Great product!']);
    }
}
