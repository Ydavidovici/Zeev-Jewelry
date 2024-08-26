<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Review;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function review_belongs_to_product()
    {
        $product = Product::factory()->create();
        $review = Review::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Product::class, $review->product);
        $this->assertEquals($product->id, $review->product->id);
    }

    #[Test]
    public function review_belongs_to_customer()
    {
        $customer = Customer::factory()->create();
        $review = Review::factory()->create(['customer_id' => $customer->id]);

        $this->assertInstanceOf(Customer::class, $review->customer);
        $this->assertEquals($customer->id, $review->customer->id);
    }
}
