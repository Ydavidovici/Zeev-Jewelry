<?php

namespace App\GraphQL\Mutations;
use Rebing\GraphQL\Support\Facades\GraphQL;

use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use App\Models\Review;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class DeleteReviewMutation extends Mutation
{
    protected $attributes = [
        'name' => 'deleteReview',
        'description' => 'Delete an existing review'
    ];

    public function type(): Type
    {
        return Type::nonNull(Type::boolean());
    }

    public function args(): array
    {
        return [
            'id' => ['type' => Type::nonNull(Type::int())],
        ];
    }

    public function resolve($root, $args)
    {
        // Rate limiting
        $user = auth()->user();
        $key = 'delete-review:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('delete-review', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Delete the review
        $review = Review::find($args['id']);
        if (!$review) {
            throw new \Exception('Review not found');
        }

        $review->delete();

        // Logging
        Log::info('Review deleted', ['user_id' => $user->id, 'review_id' => $args['id']]);

        return true;
    }
}
