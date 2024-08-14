<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Review;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;

class ReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_reviews()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        Review::factory()->count(3)->create();

        $response = $this->getJson('/api/reviews');

        $response->assertStatus(200)
            ->assertJsonStructure([[]]); // Expect an array of reviews
    }

    public function test_user_can_create_review()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $product = Product::factory()->create();
        $customer = Customer::factory()->create();

        $response = $this->postJson('/api/reviews', [
            'product_id' => $product->id,
            'customer_id' => $customer->id,
            'review_text' => 'This is a great product!',
            'rating' => 5,
            'review_date' => now()->toDateString(),
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'product_id', 'customer_id', 'review_text', 'rating', 'review_date']);
    }

    public function test_user_can_view_single_review()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $review = Review::factory()->create();

        $response = $this->getJson("/api/reviews/{$review->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'product_id', 'customer_id', 'review_text', 'rating', 'review_date']);
    }

    public function test_user_can_update_review()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $review = Review::factory()->create();

        $response = $this->putJson("/api/reviews/{$review->id}", [
            'review_text' => 'Updated review text',
            'rating' => 4,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'product_id', 'customer_id', 'review_text', 'rating', 'review_date']);
    }

    public function test_user_can_delete_review()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $review = Review::factory()->create();

        $response = $this->deleteJson("/api/reviews/{$review->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }
}
