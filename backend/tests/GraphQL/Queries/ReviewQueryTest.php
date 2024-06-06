<?php

namespace tests\GraphQL\Queries;

use Tests\TestCase;

class ReviewQueryTest extends TestCase
{
    public function testReviewQuery()
    {
        $response = $this->graphql('
            query($id: ID!) {
                review(id: $id) {
                    id
                    product {
                        id
                    }
                    customer {
                        id
                    }
                    review_text
                    rating
                    review_date
                }
            }
        ', [
            'id' => 1,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'review' => [
                    'id',
                    'product' => [
                        'id'
                    ],
                    'customer' => [
                        'id'
                    ],
                    'review_text',
                    'rating',
                    'review_date',
                ]
            ]
        ]);
    }
}
