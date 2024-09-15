<?php

namespace Tests\Feature\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Assign roles to users
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin, 'api');
    }

    #[Test]
    public function it_can_view_all_reviews()
    {
        $response = $this->getJson(route('reviews.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['*' => ['id', 'product_id', 'customer_id', 'review_text', 'rating']]);
    }

    #[Test]
    public function it_can_create_a_review()
    {
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

    #[Test]
    public function it_can_show_a_review()
    {
        $review = Review::factory()->create();

        $response = $this->getJson(route('reviews.show', $review->id));

        $response->assertStatus(200)
            ->assertJson(['id' => $review->id]);
    }

    #[Test]
    public function it_can_update_a_review()
    {
        $review = Review::factory()->create();

        $response = $this->putJson(route('reviews.update', $review->id), ['rating' => 4]);

        $response->assertStatus(200)
            ->assertJsonFragment(['rating' => 4]);

        $this->assertDatabaseHas('reviews', ['id' => $review->id, 'rating' => 4]);
    }

    #[Test]
    public function it_can_delete_a_review()
    {
        $review = Review::factory()->create();

        $response = $this->deleteJson(route('reviews.destroy', $review->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }
}
