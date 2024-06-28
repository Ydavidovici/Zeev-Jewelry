<?php

namespace App\GraphQL\Mutations;

use App\Models\Customer;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Validator;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class CreateCustomerMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createCustomer',
        'description' => 'Create a new customer',
    ];

    public function type(): Type
    {
        return GraphQL::type('Customer');
    }

    public function args(): array
    {
        return [
            'input' => [
                'type' => GraphQL::type('CustomerInput'),
                'description' => 'Input for customer',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $user = auth()->user();
        $key = 'create-customer:' . $user->id;

        // Rate limiting
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('create-customer', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Sanitize input data
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $input = $args['input'];
        $input['user_id'] = $purifier->purify($input['user_id']);
        $input['address'] = $purifier->purify($input['address']);
        $input['phone_number'] = $purifier->purify($input['phone_number']);
        $input['email'] = $purifier->purify($input['email']);
        $input['is_guest'] = filter_var($input['is_guest'], FILTER_VALIDATE_BOOLEAN);

        // Validate input data
        $validator = Validator::make($input, [
            'user_id' => 'required|integer|exists:users,id',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'email' => 'required|email|max:255|unique:customers,email',
            'is_guest' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        // Create the customer
        $customer = new Customer();
        $customer->user_id = $input['user_id'];
        $customer->address = $input['address'];
        $customer->phone_number = $input['phone_number'];
        $customer->email = $input['email'];
        $customer->is_guest = $input['is_guest'];
        $customer->save();

        // Eager load the user relationship
        $customer->load('user');

        // Logging
        Log::info('Customer created', ['user_id' => $user->id, 'customer_id' => $customer->id]);

        return $customer;
    }
}
