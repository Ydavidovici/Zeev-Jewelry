<?php

namespace App\GraphQL\Types;

use App\Models\Customer;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CustomerType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Customer',
        'description' => 'A customer',
        'model' => Customer::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the customer',
            ],
            'user' => [
                'type' => GraphQL::type('User'),
                'description' => 'The user associated with the customer',
            ],
            'address' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The address of the customer',
            ],
            'phone_number' => [
                'type' => Type::string(),
                'description' => 'The phone number of the customer',
            ],
            'email' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The email of the customer',
            ],
            'is_guest' => [
                'type' => Type::boolean(),
                'description' => 'Whether the customer is a guest',
            ],
        ];
    }
}
