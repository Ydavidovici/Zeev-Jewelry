<?php

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class CustomerInputType extends InputType
{
    protected $attributes = [
        'name' => 'CustomerInput',
        'description' => 'Input type for creating a customer',
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
                'type' => Type::nonNull(Type::string()),
                'description' => 'The phone number of the customer',
            ],
            'email' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The email of the customer',
            ],
            'is_guest' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Whether the customer is a guest',
            ],
        ];
    }
}
