<?php

namespace Tests\Feature\GraphQL;

use Tests\TestCase;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CreateReviewMutationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Set up necessary data, e.g., create a test user
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_creates_a_review()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateReview($input: ReviewInput!) {
                    createReview(input: $input) {
                        id
                        product_id
                        customer_id
                        review_text
                        rating
                        review_date
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'customer_id' => 1, // Ensure a customer with this ID exists in your test setup
                    'review_text' => 'Great product!',
                    'rating' => 5,
                ],
            ],
        ]);

        $response->assertJson([
            'data' => [
                'createReview' => [
                    'product_id' => 1,
                    'customer_id' => 1,
                    'review_text' => 'Great product!',
                    'rating' => 5,
                ],
            ],
        ]);
    }

    /** @test */
    public function it_fails_with_missing_required_fields()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateReview($input: ReviewInput!) {
                    createReview(input: $input) {
                        id
                        product_id
                        customer_id
                        review_text
                        rating
                        review_date
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    // Missing customer_id, review_text, rating
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The customer id field is required.');
    }

    /** @test */
    public function it_fails_with_invalid_data_types()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateReview($input: ReviewInput!) {
                    createReview(input: $input) {
                        id
                        product_id
                        customer_id
                        review_text
                        rating
                        review_date
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'customer_id' => 'one', // Invalid data type
                    'review_text' => 12345, // Invalid data type
                    'rating' => 'five', // Invalid data type
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The customer id must be an integer.');
    }

    /** @test */
    public function it_sanitizes_inputs()
    {
        // Assuming HTMLPurifier is used to sanitize inputs, this can be tested indirectly
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateReview($input: ReviewInput!) {
                    createReview(input: $input) {
                        id
                        product_id
                        customer_id
                        review_text
                        rating
                        review_date
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'customer_id' => 1, // Ensure a customer with this ID exists in your test setup
                    'review_text' => '<script>alert(1)</script>',
                    'rating' => 5,
                ],
            ],
        ]);

        // This should succeed but the sanitized review_text should be saved
        $response->assertJson([
            'data' => [
                'createReview' => [
                    'review_text' => '&lt;script&gt;alert(1)&lt;/script&gt;',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_requires_authorization()
    {
        // Temporarily disable Gate to simulate unauthorized access
        Gate::shouldReceive('denies')->with('create-review', $this->user)->andReturn(true);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateReview($input: ReviewInput!) {
                    createReview(input: $input) {
                        id
                        product_id
                        customer_id
                        review_text
                        rating
                        review_date
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'customer_id' => 1, // Ensure a customer with this ID exists in your test setup
                    'review_text' => 'Great product!',
                    'rating' => 5,
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('Unauthorized');
    }

    /** @test */
    public function it_enforces_rate_limiting()
    {
        RateLimiter::hit('create-review:' . $this->user->id, 5);

        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateReview($input: ReviewInput!) {
                    createReview(input: $input) {
                        id
                        product_id
                        customer_id
                        review_text
                        rating
                        review_date
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'customer_id' => 1, // Ensure a customer with this ID exists in your test setup
                    'review_text' => 'Great product!',
                    'rating' => 5,
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('Too many attempts. Please try again later.');
    }

    /** @test */
    public function it_logs_actions()
    {
        Log::shouldReceive('info')->once()->with('Review created', ['user_id' => $this->user->id, 'review_id' => 1]);

        $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateReview($input: ReviewInput!) {
                    createReview(input: $input) {
                        id
                        product_id
                        customer_id
                        review_text
                        rating
                        review_date
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'customer_id' => 1, // Ensure a customer with this ID exists in your test setup
                    'review_text' => 'Great product!',
                    'rating' => 5,
                ],
            ],
        ]);
    }

    /** @test */
    public function it_measures_performance()
    {
        // Measure performance (this is a basic example, for real performance testing consider using a dedicated tool)
        $startTime = microtime(true);

        $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateReview($input: ReviewInput!) {
                    createReview(input: $input) {
                        id
                        product_id
                        customer_id
                        review_text
                        rating
                        review_date
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'customer_id' => 1, // Ensure a customer with this ID exists in your test setup
                    'review_text' => 'Great product!',
                    'rating' => 5,
                ],
            ],
        ]);

        $endTime = microtime(true);
        $duration = $endTime - $startTime;

        $this->assertLessThan(1, $duration, 'Mutation took too long'); // Example threshold, adjust as needed
    }

    /** @test */
    public function it_is_secure()
    {
        // Test for SQL injection (example, assuming input is sanitized)
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateReview($input: ReviewInput!) {
                    createReview(input: $input) {
                        id
                        product_id
                        customer_id
                        review_text
                        rating
                        review_date
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'customer_id' => 1, // Ensure a customer with this ID exists in your test setup
                    'review_text' => '1 OR 1=1',
                    'rating' => 5,
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The given data was invalid.');
    }

    /** @test */
    public function it_handles_validation_errors()
    {
        $response = $this->actingAs($this->user)->postGraphQL([
            'query' => '
                mutation CreateReview($input: ReviewInput!) {
                    createReview(input: $input) {
                        id
                        product_id
                        customer_id
                        review_text
                        rating
                        review_date
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'product_id' => 1, // Ensure a product with this ID exists in your test setup
                    'customer_id' => 1, // Ensure a customer with this ID exists in your test setup
                    'review_text' => '', // Invalid input
                    'rating' => 5,
                ],
            ],
        ]);

        $response->assertGraphQLErrorMessage('The review text field is required.');
    }
}
