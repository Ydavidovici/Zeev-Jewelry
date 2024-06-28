<?php

namespace App\GraphQL\Mutations;

use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;


class UpdateCustomerMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateCustomer',
        'description' => 'Update an existing customer'
    ];

    public function type(): Type
    {
        return GraphQL::type('Customer');
    }

    public function args(): array
    {
        return [
            'id' => ['type' => Type::nonNull(Type::int())],
            'input' => ['type' => GraphQL::type('CustomerInput')],
        ];
    }

    public function resolve($root, $args)
    {
        // Rate limiting
        $user = auth()->user();
        $key = 'update-customer:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('update-customer', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Validate and update the customer
        $customer = Customer::find($args['id']);
        if (!$customer) {
            throw new \Exception('Customer not found');
        }

        $validator = Validator::make($args['input'], [
            'name' => 'required|string|max:255',
            // Add other validation rules as necessary
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $customer->update($args['input']);

        // Logging
        Log::info('Customer updated', ['user_id' => $user->id, 'customer_id' => $args['id']]);

        return $customer;
    }
}
