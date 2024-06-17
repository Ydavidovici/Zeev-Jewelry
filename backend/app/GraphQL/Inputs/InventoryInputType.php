<?php

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType as GraphQLInputType;

class InventoryInputType extends GraphQLInputType
{
    protected $attributes = [
        'name' => 'InventoryInput',
        'description' => 'An input type for inventory',
    ];

    public function fields(): array
    {
        return [
            'product_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the product in the inventory',
            ],
            'quantity' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The quantity of the product',
            ],
            'location' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The location of the inventory',
            ],
        ];
    }
}
