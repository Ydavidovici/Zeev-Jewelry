<?php

namespace App\GraphQL\Mutations;
use Rebing\GraphQL\Support\Facades\GraphQL;

use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use App\Models\Shipping;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class UpdateShippingMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateShipping',
        'description' => 'Update an existing shipping record'
    ];

    public function type(): Type
    {
        return GraphQL::type('Shipping');
    }

    public function args(): array
    {
        return [
            'id' => ['type' => Type::nonNull(Type::int())],
            'input' => ['type' => GraphQL::type('ShippingInput')],
        ];
    }

    public function resolve($root, $args)
    {
        // Rate limiting
        $user = auth()->user();
        $key = 'update-shipping:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('update-shipping', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Validate and update the shipping record
        $shipping = Shipping::find($args['id']);
        if (!$shipping) {
            throw new \Exception('Shipping record not found');
        }

        $validator = Validator::make($args['input'], [
            'address' => 'required|string|max:255',
            // Add other validation rules as necessary
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $shipping->update($args['input']);

        // Logging
        Log::info('Shipping updated', ['user_id' => $user->id, 'shipping_id' => $args['id']]);

        return $shipping;
    }
}
