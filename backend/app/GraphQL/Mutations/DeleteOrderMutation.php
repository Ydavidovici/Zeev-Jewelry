<?php

namespace App\GraphQL\Mutations;
use Rebing\GraphQL\Support\Facades\GraphQL;

use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use App\Models\Order;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class DeleteOrderMutation extends Mutation
{
    protected $attributes = [
        'name' => 'deleteOrder',
        'description' => 'Delete an existing order'
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
        $key = 'delete-order:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('delete-order', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Delete the order
        $order = Order::find($args['id']);
        if (!$order) {
            throw new \Exception('Order not found');
        }

        $order->delete();

        // Logging
        Log::info('Order deleted', ['user_id' => $user->id, 'order_id' => $args['id']]);

        return true;
    }
}
