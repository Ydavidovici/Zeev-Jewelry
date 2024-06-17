<?php

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType as GraphQLInputType;

class OrderInputType extends GraphQLInputType
{
    protected $attributes = [
        'name' => 'OrderInput',
        'description' => 'An input type for orders',
    ];

    public function fields(): array
    {
        return [
            'customer_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the customer who placed the order',
            ],
            'order_date' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The date the order was placed',
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
