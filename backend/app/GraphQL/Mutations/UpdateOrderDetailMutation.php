<?php

namespace App\GraphQL\Mutations;
use Rebing\GraphQL\Support\Facades\GraphQL;

use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class UpdateOrderDetailMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateOrderDetail',
        'description' => 'Update an existing order detail'
    ];

    public function type(): Type
    {
        return GraphQL::type('OrderDetail');
    }

    public function args(): array
    {
        return [
            'id' => ['type' => Type::nonNull(Type::int())],
            'input' => ['type' => GraphQL::type('OrderDetailInput')],
        ];
    }

    public function resolve($root, $args)
    {
        // Rate limiting
        $user = auth()->user();
        $key = 'update-order-detail:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('update-order-detail', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Validate and update the order detail
        $orderDetail = OrderDetail::find($args['id']);
        if (!$orderDetail) {
            throw new \Exception('Order detail not found');
        }

        $validator = Validator::make($args['input'], [
            'quantity' => 'required|integer|min:1',
            // Add other validation rules as necessary
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $orderDetail->update($args['input']);

        // Logging
        Log::info('Order detail updated', ['user_id' => $user->id, 'order_detail_id' => $args['id']]);

        return $orderDetail;
    }
}
