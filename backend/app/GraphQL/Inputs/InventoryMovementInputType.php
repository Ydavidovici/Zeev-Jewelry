<?php

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType as GraphQLInputType;

class InventoryMovementInputType extends GraphQLInputType
{
    protected $attributes = [
        'name' => 'InventoryMovementInput',
        'description' => 'An input type for inventory movement',
    ];

    public function fields(): array
    {
        return [
            'inventory_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the inventory related to the movement',
            ],
            'quantity' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The quantity moved',
            ],
            'movement_type' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The type of movement (e.g., IN, OUT)',
            ],
            'movement_date' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The date of the movement',
            ],
        ];
    }
}
