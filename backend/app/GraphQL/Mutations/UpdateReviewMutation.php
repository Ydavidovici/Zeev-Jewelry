<?php

namespace App\GraphQL\Mutations;
use Rebing\GraphQL\Support\Facades\GraphQL;

use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use App\Models\Review;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class UpdateReviewMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateReview',
        'description' => 'Update an existing review'
    ];

    public function type(): Type
    {
        return GraphQL::type('Review');
    }

    public function args(): array
    {
        return [
            'id' => ['type' => Type::nonNull(Type::int())],
            'input' => ['type' => GraphQL::type('ReviewInput')],
        ];
    }

    public function resolve($root, $args)
    {
        // Rate limiting
        $user = auth()->user();
        $key = 'update-review:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('update-review', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Validate and update the review
        $review = Review::find($args['id']);
        if (!$review) {
            throw new \Exception('Review not found');
        }

        $validator = Validator::make($args['input'], [
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            // Add other validation rules as necessary
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $review->update($args['input']);

        // Logging
        Log::info('Review updated', ['user_id' => $user->id, 'review_id' => $args['id']]);

        return $review;
    }
}
