<?php

namespace App\GraphQL\Mutations;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use App\Models\Customer;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class DeleteCustomerMutation extends Mutation
{
    protected $attributes = [
        'name' => 'deleteCustomer',
        'description' => 'Delete an existing customer'
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
        $key = 'delete-customer:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('delete-customer', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Delete the customer
        $customer = Customer::find($args['id']);
        if (!$customer) {
            throw new \Exception('Customer not found');
        }

        $customer->delete();

        // Logging
        Log::info('Customer deleted', ['user_id' => $user->id, 'customer_id' => $args['id']]);

        return true;
    }
}
