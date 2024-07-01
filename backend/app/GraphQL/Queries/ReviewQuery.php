<?php

namespace App\GraphQL\Queries;

use App\Models\Review;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use HTMLPurifier;
use HTMLPurifier_Config;

class ReviewQuery extends Query
{
    protected $attributes = [
        'name' => 'review',
    ];

    public function type(): Type
    {
        return GraphQL::type('Review');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The id of the review',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $user = auth()->user();
        $key = 'review-query:' . $user->id;

        // Rate limiting
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('view-review', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Sanitize input data
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $args['id'] = $purifier->purify($args['id']);

        // Validate input data
        $validator = Validator::make($args, [
            'id' => 'required|integer|exists:reviews,id',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        // Fetch the review
        $review = Review::find($args['id']);

        // Error handling
        if (!$review) {
            throw new \Exception('Review not found');
        }

        // Logging
        Log::info('Review queried', ['user_id' => $user->id, 'review_id' => $args['id']]);

        return $review;
    }
}
