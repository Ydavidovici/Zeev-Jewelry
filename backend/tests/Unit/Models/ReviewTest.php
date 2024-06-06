<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_review()
    {
        $review = Review::factory()->create([
            'product_id' => 1,
            'customer_id' => 1,
            'review_text' => 'Great product!',
            'rating' => 5,
        ]);

        $this->assertDatabaseHas('reviews', ['review_text' => 'Great product!']);
    }
}
