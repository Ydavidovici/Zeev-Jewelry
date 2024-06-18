<?php

namespace App\GraphQL\Queries;

use App\Models\Order;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use HTMLPurifier;
use HTMLPurifier_Config;

class OrderQuery extends Query
{
    protected $attributes = [
        'name' => 'order',
    ];

    public function type(): Type
    {
        return GraphQL::type('Order');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The id of the order',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $user = auth()->user();
        $key = 'order-query:' . $user->id;

        // Rate limiting
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('view-order', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Sanitize input data
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $args['id'] = $purifier->purify($args['id']);

        // Validate input data
        $validator = Validator::make($args, [
            'id' => 'required|integer|exists:orders,id',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        // Fetch the order
        $order = Order::find($args['id']);

        // Error handling
        if (!$order) {
            throw new \Exception('Order not found');
        }

        // Logging
        Log::info('Order queried', ['user_id' => $user->id, 'order_id' => $args['id']]);

        return $order;
    }
}
