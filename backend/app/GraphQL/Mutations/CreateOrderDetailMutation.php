<?php

namespace App\GraphQL\Mutations;

use App\Models\OrderDetail;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Validator;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class CreateOrderDetailMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createOrderDetail',
        'description' => 'Create a new order detail',
    ];

    public function type(): Type
    {
        return GraphQL::type('OrderDetail');
    }

    public function args(): array
    {
        return [
            'input' => [
                'type' => GraphQL::type('OrderDetailInput'),
                'description' => 'Input for order detail',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $user = auth()->user();
        $key = 'create-order-detail:' . $user->id;

        // Rate limiting
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('create-order-detail', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Sanitize input data
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $input = $args['input'];
        $input['order_id'] = $purifier->purify($input['order_id']);
        $input['product_id'] = $purifier->purify($input['product_id']);
        $input['quantity'] = $purifier->purify($input['quantity']);
        $input['price'] = $purifier->purify($input['price']);

        // Validate input data
        $validator = Validator::make($input, [
            'order_id' => 'required|integer|exists:orders,id',
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        // Create the order detail
        $orderDetail = new OrderDetail();
        $orderDetail->order_id = $input['order_id'];
        $orderDetail->product_id = $input['product_id'];
        $orderDetail->quantity = $input['quantity'];
        $orderDetail->price = $input['price'];
        $orderDetail->save();

        // Logging
        Log::info('Order detail created', ['user_id' => $user->id, 'order_detail_id' => $orderDetail->id]);

        return $orderDetail;
    }
}
