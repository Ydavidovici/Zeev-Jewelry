<?php

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class OrderInputType extends InputType
{
    protected $attributes = [
        'name' => 'OrderInput',
        'description' => 'Input type for order',
    ];

    public function fields(): array
    {
        return [
            'customer_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the customer placing the order',
            ],
            'total_amount' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'The total amount of the order',
            ],
            'is_guest' => [
                'type' => Type::boolean(),
                'description' => 'Whether the order was placed by a guest',
            ],
            'status' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The status of the order',
            ],
        ];
    }
}
