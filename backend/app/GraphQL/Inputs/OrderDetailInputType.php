<?php

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType as GraphQLInputType;

class OrderDetailInputType extends GraphQLInputType
{
    protected $attributes = [
        'name' => 'OrderDetailInput',
        'description' => 'An input type for order details',
    ];

    public function fields(): array
    {
        return [
            'order_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the order associated with the detail',
            ],
            'product_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the product in the order detail',
            ],
            'quantity' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The quantity of the product',
            ],
            'price' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'The price of the product',
            ],
        ];
    }
}
