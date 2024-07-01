<?php

namespace App\GraphQL\Mutations;

use App\Models\Order;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Validator;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class CreateOrderMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createOrder',
        'description' => 'Create a new order',
    ];

    public function type(): Type
    {
        return GraphQL::type('Order');
    }

    public function args(): array
    {
        return [
            'input' => [
                'type' => GraphQL::type('OrderInput'),
                'description' => 'Input for order',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $user = auth()->user();
        $key = 'create-order:' . $user->id;

        // Rate limiting
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('create-order', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Sanitize input data
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $input = $args['input'];
        $input['customer_id'] = $purifier->purify($input['customer_id']);
        $input['total_amount'] = $purifier->purify($input['total_amount']);
        $input['is_guest'] = $purifier->purify($input['is_guest']);
        $input['status'] = $purifier->purify($input['status']);

        // Validate input data
        $validator = Validator::make($input, [
            'customer_id' => 'required|integer|exists:customers,id',
            'total_amount' => 'required|numeric|min:0',
            'is_guest' => 'required|boolean',
            'status' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        // Create the order
        $order = new Order();
        $order->customer_id = $input['customer_id'];
        $order->order_date = now();
        $order->total_amount = $input['total_amount'];
        $order->is_guest = $input['is_guest'];
        $order->status = $input['status'];
        $order->save();

        // Logging
        Log::info('Order created', ['user_id' => $user->id, 'order_id' => $order->id]);

        return $order;
    }
}
