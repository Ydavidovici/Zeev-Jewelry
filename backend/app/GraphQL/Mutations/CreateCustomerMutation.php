<?php

namespace App\GraphQL\Mutations;

use App\Models\Customer;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

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
        $input = $args['input'];
        $customer = new Customer();
        $customer->user_id = $input['user_id'];
        $customer->address = $input['address'];
        $customer->phone_number = $input['phone_number'];
        $customer->email = $input['email'];
        $customer->is_guest = $input['is_guest'];
        $customer->save();

        return $customer;
    }
}
