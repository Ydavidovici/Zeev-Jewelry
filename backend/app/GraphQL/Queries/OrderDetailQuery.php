<?php

namespace App\GraphQL\Queries;

use App\Models\OrderDetail;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use HTMLPurifier;
use HTMLPurifier_Config;

class OrderDetailQuery extends Query
{
    protected $attributes = [
        'name' => 'orderDetail',
    ];

    public function type(): Type
    {
        return GraphQL::type('OrderDetail');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The id of the order detail',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $user = auth()->user();
        $key = 'order-detail-query:' . $user->id;

        // Rate limiting
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('view-order-detail', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Sanitize input data
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $args['id'] = $purifier->purify($args['id']);

        // Validate input data
        $validator = Validator::make($args, [
            'id' => 'required|integer|exists:order_details,id',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        // Fetch the order detail
        $orderDetail = OrderDetail::find($args['id']);

        // Error handling
        if (!$orderDetail) {
            throw new \Exception('Order detail not found');
        }

        // Logging
        Log::info('Order detail queried', ['user_id' => $user->id, 'order_detail_id' => $args['id']]);

        return $orderDetail;
    }
}
