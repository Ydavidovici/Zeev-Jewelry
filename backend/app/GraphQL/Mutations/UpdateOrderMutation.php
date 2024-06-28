<?php

namespace App\GraphQL\Mutations;
use Rebing\GraphQL\Support\Facades\GraphQL;

use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class UpdateOrderMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateOrder',
        'description' => 'Update an existing order'
    ];

    public function type(): Type
    {
        return GraphQL::type('Order');
    }

    public function args(): array
    {
        return [
            'id' => ['type' => Type::nonNull(Type::int())],
            'input' => ['type' => GraphQL::type('OrderInput')],
        ];
    }

    public function resolve($root, $args)
    {
        // Rate limiting
        $user = auth()->user();
        $key = 'update-order:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('update-order', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Validate and update the order
        $order = Order::find($args['id']);
        if (!$order) {
            throw new \Exception('Order not found');
        }

        $validator = Validator::make($args['input'], [
            'status' => 'required|string|max:255',
            // Add other validation rules as necessary
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $order->update($args['input']);

        // Logging
        Log::info('Order updated', ['user_id' => $user->id, 'order_id' => $args['id']]);

        return $order;
    }
}
