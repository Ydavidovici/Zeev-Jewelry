<?php

namespace App\GraphQL\Mutations;

use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class DeleteOrderDetailMutation extends Mutation
{
    protected $attributes = [
        'name' => 'deleteOrderDetail',
        'description' => 'Delete an existing order detail'
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
        $key = 'delete-order-detail:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('delete-order-detail', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Delete the order detail
        $orderDetail = OrderDetail::find($args['id']);
        if (!$orderDetail) {
            throw new \Exception('Order detail not found');
        }

        $orderDetail->delete();

        // Logging
        Log::info('Order detail deleted', ['user_id' => $user->id, 'order_detail_id' => $args['id']]);

        return true;
    }
}
