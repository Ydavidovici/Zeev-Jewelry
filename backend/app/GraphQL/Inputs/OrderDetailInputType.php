<?php

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class OrderDetailInputType extends InputType
{
    protected $attributes = [
        'name' => 'OrderDetailInput',
        'description' => 'Input type for order detail',
    ];

    public function fields(): array
    {
        return [
            'order_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the order',
            ],
            'product_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the product',
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
