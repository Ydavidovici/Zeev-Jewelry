<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Review;
use App\Models\User;
use App\Models\Product;
use App\Models\Customer;

class ReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and set them as the current authenticated user
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function user_can_view_reviews_index()
    {
        $response = $this->get(route('reviews.index'));

        $response->assertStatus(200);
        $response->assertViewIs('reviews.index');
        $response->assertViewHas('reviews');
    }

    /** @test */
    public function user_can_view_create_review_form()
    {
        $response = $this->get(route('reviews.create'));

        $response->assertStatus(200);
        $response->assertViewIs('reviews.create');
    }

    /** @test */
    public function user_can_create_review()
    {
        $product = Product::factory()->create();
        $customer = Customer::factory()->create();

        $data = [
            'product_id' => $product->id,
            'customer_id' => $customer->id,
            'review_text' => 'Great product!',
            'rating' => 5,
            'review_date' => now()->format('Y-m-d'),
        ];

        $response = $this->post(route('reviews.store'), $data);

        $response->assertRedirect(route('reviews.index'));
        $this->assertDatabaseHas('reviews', $data);
    }

    /** @test */
    public function user_can_view_edit_review_form()
    {
        $review = Review::factory()->create();

        $response = $this->get(route('reviews.edit', $review));

        $response->assertStatus(200);
        $response->assertViewIs('reviews.edit');
        $response->assertViewHas('review', $review);
    }

    /** @test */
    public function user_can_update_review()
    {
        $review = Review::factory()->create();

        $data = [
            'product_id' => $review->product_id,
            'customer_id' => $review->customer_id,
            'review_text' => 'Updated review text',
            'rating' => 4,
            'review_date' => now()->format('Y-m-d'),
        ];

        $response = $this->put(route('reviews.update', $review), $data);

        $response->assertRedirect(route('reviews.index'));
        $this->assertDatabaseHas('reviews', array_merge(['id' => $review->id], $data));
    }

    /** @test */
    public function user_can_delete_review()
    {
        $review = Review::factory()->create();

        $response = $this->delete(route('reviews.destroy', $review));

        $response->assertRedirect(route('reviews.index'));
        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }
}
