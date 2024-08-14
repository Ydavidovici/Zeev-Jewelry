<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Review;

class ReviewTest extends TestCase
{
    public function test_review_has_product_id()
    {
        $review = new Review(['product_id' => 1]);

        $this->assertEquals(1, $review->product_id);
    }

    public function test_review_has_customer_id()
    {
        $review = new Review(['customer_id' => 1]);

        $this->assertEquals(1, $review->customer_id);
    }

    public function test_review_has_review_text()
    {
        $review = new Review(['review_text' => 'Great product!']);

        $this->assertEquals('Great product!', $review->review_text);
    }

    public function test_review_has_rating()
    {
        $review = new Review(['rating' => 5]);

        $this->assertEquals(5, $review->rating);
    }

    public function test_review_has_review_date()
    {
        $review = new Review(['review_date' => '2024-07-24']);

        $this->assertEquals('2024-07-24', $review->review_date);
    }

    public function test_review_belongs_to_product()
    {
        $review = new Review();
        $relation = $review->product();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $relation);
        $this->assertEquals('product_id', $relation->getForeignKeyName());
    }

    public function test_review_belongs_to_customer()
    {
        $review = new Review();
        $relation = $review->customer();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $relation);
        $this->assertEquals('customer_id', $relation->getForeignKeyName());
    }
}
