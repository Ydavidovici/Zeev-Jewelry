<?php

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType as GraphQLInputType;

class CustomerInputType extends GraphQLInputType
{
    protected $attributes = [
        'name' => 'CustomerInput',
        'description' => 'An input type for customer',
    ];

    public function fields(): array
    {
        return [
            'user_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the user associated with the customer',
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
