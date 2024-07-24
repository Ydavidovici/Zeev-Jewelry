<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Review;
use App\Models\Product;
use App\Models\Customer;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_review()
    {
        $product = Product::factory()->create();
        $customer = Customer::factory()->create();
        $data = [
            'product_id' => $product->id,
            'customer_id' => $customer->id,
            'review_text' => 'Great product!',
            'rating' => 5,
            'review_date' => '2024-07-24',
        ];

        $response = $this->post(route('reviews.store'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('reviews', ['product_id' => $product->id, 'review_text' => 'Great product!']);
    }

    public function test_read_review()
    {
        $review = Review::factory()->create();

        $response = $this->get(route('reviews.show', $review->id));

        $response->assertStatus(200);
        $response->assertJson($review->toArray());
    }

    public function test_update_review()
    {
        $review = Review::factory()->create();
        $data = [
            'review_text' => 'Updated review text',
            'rating' => 4,
        ];

        $response = $this->put(route('reviews.update', $review->id), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('reviews', ['id' => $review->id, 'review_text' => 'Updated review text']);
    }

    public function test_delete_review()
    {
        $review = Review::factory()->create();
        $reviewId = $review->id;

        $response = $this->delete(route('reviews.destroy', $reviewId));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('reviews', ['id' => $reviewId]);
    }
}
