<?php

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class InventoryInputType extends InputType
{
    protected $attributes = [
        'name' => 'InventoryInput',
        'description' => 'Input type for inventory',
    ];

    public function fields(): array
    {
        return [
            'product_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The product ID of the inventory',
            ],
            'quantity' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The quantity of the product in inventory',
            ],
            'location' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The location of the inventory',
            ],
        ];
    }
}
