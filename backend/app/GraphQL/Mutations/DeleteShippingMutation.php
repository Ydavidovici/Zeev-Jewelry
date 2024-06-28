<?php

namespace App\GraphQL\Mutations;
use Rebing\GraphQL\Support\Facades\GraphQL;

use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use App\Models\Shipping;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class DeleteShippingMutation extends Mutation
{
    protected $attributes = [
        'name' => 'deleteShipping',
        'description' => 'Delete an existing shipping record'
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
        $key = 'delete-shipping:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('delete-shipping', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Delete the shipping record
        $shipping = Shipping::find($args['id']);
        if (!$shipping) {
            throw new \Exception('Shipping record not found');
        }

        $shipping->delete();

        // Logging
        Log::info('Shipping record deleted', ['user_id' => $user->id, 'shipping_id' => $args['id']]);

        return true;
    }
}
