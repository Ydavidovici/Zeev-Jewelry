<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testReviewIndex()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        Review::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/reviews');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function testReviewStore()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $data = [
            'product_id' => 1,
            'customer_id' => 1,
            'review_text' => 'Great product!',
            'rating' => 5,
            'review_date' => now(),
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/reviews', $data);

        $response->assertStatus(201)
            ->assertJson($data);
    }

    public function testReviewShow()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $review = Review::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/reviews/{$review->id}");

        $response->assertStatus(200)
            ->assertJson($review->toArray());
    }

    public function testReviewUpdate()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $review = Review::factory()->create();

        $data = [
            'review_text' => 'Updated review',
            'rating' => 4,
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/reviews/{$review->id}", $data);

        $response->assertStatus(200)
            ->assertJson($data);
    }

    public function testReviewDestroy()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $review = Review::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->deleteJson("/api/reviews/{$review->id}");

        $response->assertStatus(204);
    }
}
