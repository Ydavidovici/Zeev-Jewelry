<?php

namespace Tests\GraphQL;

use Tests\TestCase;

class ReviewCRUDTest extends TestCase
{
    public function testReviewCRUDOperations()
    {
        // Create a new review
        $createResponse = $this->graphql('
            mutation {
                createReview(input: {
                    product_id: 1,
                    rating: 5,
                    comment: "Great product!"
                }) {
                    id
                    product_id
                    rating
                    comment
                }
            }
        ');

        $createResponse->assertJsonStructure([
            'data' => [
                'createReview' => [
                    'id',
                    'product_id',
                    'rating',
                    'comment'
                ]
            ]
        ]);

        $reviewId = $createResponse->json('data.createReview.id');

        // Read the created review
        $readResponse = $this->graphql('
            query {
                review(id: ' . $reviewId . ') {
                    id
                    product_id
                    rating
                    comment
                }
            }
        ');

        $readResponse->assertJson([
            'data' => [
                'review' => [
                    'id' => $reviewId,
                    'product_id' => 1,
                    'rating' => 5,
                    'comment' => 'Great product!'
                ]
            ]
        ]);

        // Update the review
        $updateResponse = $this->graphql('
            mutation {
                updateReview(id: ' . $reviewId . ', input: {
                    rating: 4
                }) {
                    id
                    product_id
                    rating
                    comment
                }
            }
        ');

        $updateResponse->assertJson([
            'data' => [
                'updateReview' => [
                    'id' => $reviewId,
                    'product_id' => 1,
                    'rating' => 4,
                    'comment' => 'Great product!'
                ]
            ]
        ]);

        // Delete the review
        $deleteResponse = $this->graphql('
            mutation {
                deleteReview(id: ' . $reviewId . ') {
                    id
                }
            }
        ');

        $deleteResponse->assertJson([
            'data' => [
                'deleteReview' => [
                    'id' => $reviewId
                ]
            ]
        ]);

        // Verify the review has been deleted
        $readAfterDeleteResponse = $this->graphql('
            query {
                review(id: ' . $reviewId . ') {
                    id
                    product_id
                    rating
                    comment
                }
            }
        ');

        $readAfterDeleteResponse->assertJson([
            'data' => [
                'review' => null
            ]
        ]);
    }
}
