<?php

namespace tests\GraphQL\Inputs;

use Tests\TestCase;

class ReviewInputTypeTest extends TestCase
{
    public function testReviewInputType()
    {
        $response = $this->graphql('
            mutation($input: ReviewInput!) {
                createReview(input: $input) {
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
            'input' => [
                'product_id' => 1,
                'customer_id' => 1,
                'review_text' => 'Great product!',
                'rating' => 5,
                'review_date' => now()
            ],
            Here are the remaining test files for the input types:

        **ReviewInputTypeTest.php:**

```php
<?php

namespace Tests\GraphQL\Inputs;

use Tests\TestCase;

class ReviewInputTypeTest extends TestCase
{
    public function testReviewInputType()
    {
        $response = $this->graphql('
            mutation($input: ReviewInput!) {
                createReview(input: $input) {
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
            'input' => [
                'product_id' => 1,
                'customer_id' => 1,
                'review_text' => 'Great product!',
                'rating' => 5,
                'review_date' => now()
            ],
        ]);

        $response->assertJsonStructure([
            'data' => [
                'createReview' => [
                    'id',
                    'product' => [
                        'id'
                    ],
                    'customer' => [
                        'id'
                    ],
                    'review_text',
                    'rating',
                    'review_date'
                ]
            ]
        ]);
    }
}
