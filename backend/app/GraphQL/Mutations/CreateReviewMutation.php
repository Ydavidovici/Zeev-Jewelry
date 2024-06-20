<?php

namespace App\GraphQL\Mutations;

use App\Models\Review;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Validator;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

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
        $user = auth()->user();
        $key = 'create-review:' . $user->id;

        // Rate limiting
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('create-review', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Sanitize input data
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $input = $args['input'];
        $input['product_id'] = $purifier->purify($input['product_id']);
        $input['customer_id'] = $purifier->purify($input['customer_id']);
        $input['review_text'] = $purifier->purify($input['review_text']);
        $input['rating'] = $purifier->purify($input['rating']);

        // Validate input data
        $validator = Validator::make($input, [
            'product_id' => 'required|integer|exists:products,id',
            'customer_id' => 'required|integer|exists:customers,id',
            'review_text' => 'required|string|max:1000',
            'rating' => 'required|integer|between:1,5',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        // Create the review
        $review = new Review();
        $review->product_id = $input['product_id'];
        $review->customer_id = $input['customer_id'];
        $review->review_text = $input['review_text'];
        $review->rating = $input['rating'];
        $review->review_date = now();
        $review->save();

        // Logging
        Log::info('Review created', ['user_id' => $user->id, 'review_id' => $review->id]);

        return $review;
    }
}
