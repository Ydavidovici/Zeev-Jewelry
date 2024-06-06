<?php

namespace tests\GraphQL\Mutations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function Tests\Feature\GraphQL\Mutations\now;

class CreateReviewMutationTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_review_mutation()
    {
        $mutation = '
            mutation($input: ReviewInput!) {
                createReview(input: $input) {
                    id
                    review_text
                    rating
                }
            }
        ';

        $variables = [
            'input' => [
                'product_id' => 1,
                'customer_id' => 1,
                'review_text' => 'Excellent product!',
                'rating' => 5,
                'review_date' => now()->toDateTimeString()
            ]
        ];

        $response = $this->graphQL($mutation, $variables);

        $response->assertJson([
            'data' => [
                'createReview' => [
                    'review_text' => 'Excellent product!',
                    'rating' => 5
                ]
            ]
        ]);
    }
}
