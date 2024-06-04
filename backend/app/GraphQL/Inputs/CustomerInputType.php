<?php

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class CustomerInputType extends InputType
{
    protected $attributes = [
        'name' => 'CustomerInput',
        'description' => 'Input type for customer',
    ];

    public function fields(): array
    {
        return [
            'user_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the associated user',
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
