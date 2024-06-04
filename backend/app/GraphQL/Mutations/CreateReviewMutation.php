<?php

namespace App\GraphQL\Mutations;

use App\Models\Review;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class CreateReviewMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createReview',
        'description' => 'Create a new review',
    ];

    public function type(): Type
    {
        return GraphQL::type('Review');
    }

    public function args(): array
    {
        return [
            'input' => [
                'type' => GraphQL::type('ReviewInput'),
                'description' => 'Input for review',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $input = $args['input'];
        $review = new Review();
        $review->product_id = $input['product_id'];
        $review->customer_id = $input['customer_id'];
        $review->review_text = $input['review_text'];
        $review->rating = $input['rating'];
        $review->review_date = now();
        $review->save();

        return $review;
    }
}
