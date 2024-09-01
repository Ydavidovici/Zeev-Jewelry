<?php

namespace Tests\Feature\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class ReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create(), 'api');
    }

    /** @test */
    public function it_can_view_all_reviews()
    {
        Gate::define('viewAny', function ($user) {
            return true;
        });

        $response = $this->getJson(route('reviews.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['*' => ['id', 'product_id', 'customer_id', 'review_text', 'rating']]);
    }

    /** @test */
    public function it_can_create_a_review()
    {
        Gate::define('create', function ($user) {
            return true;
        });

        $reviewData = [
            'product_id' => 1,
            'customer_id' => 1,
            'review_text' => 'Great product!',
            'rating' => 5,
            'review_date' => now()->toDateString(),
        ];

        $response = $this->postJson(route('reviews.store'), $reviewData);

        $response->assertStatus(201)
            ->assertJsonFragment(['review_text' => 'Great product!']);

        $this->assertDatabaseHas('reviews', ['product_id' => 1, 'review_text' => 'Great product!']);
    }

    /** @test */
    public function it_can_show_a_review()
    {
        Gate::define('view', function ($user, $review) {
            return true;
        });

        $review = Review::factory()->create();

        $response = $this->getJson(route('reviews.show', $review->id));

        $response->assertStatus(200)
            ->assertJson(['id' => $review->id]);
    }

    /** @test */
    public function it_can_update_a_review()
    {
        Gate::define('update', function ($user, $review) {
            return true;
        });

        $review = Review::factory()->create();

        $response = $this->putJson(route('reviews.update', $review->id), ['rating' => 4]);

        $response->assertStatus(200)
            ->assertJsonFragment(['rating' => 4]);

        $this->assertDatabaseHas('reviews', ['id' => $review->id, 'rating' => 4]);
    }

    /** @test */
    public function it_can_delete_a_review()
    {
        Gate::define('delete', function ($user, $review) {
            return true;
        });

        $review = Review::factory()->create();

        $response = $this->deleteJson(route('reviews.destroy', $review->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }
}
