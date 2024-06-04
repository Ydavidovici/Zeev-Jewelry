<?php

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class InventoryMovementInputType extends InputType
{
    protected $attributes = [
        'name' => 'InventoryMovementInput',
        'description' => 'Input type for inventory movement',
    ];

    public function fields(): array
    {
        return [
            'inventory_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The inventory ID of the movement',
            ],
            'type' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The type of movement (addition or subtraction)',
            ],
            'quantity_change' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The change in quantity',
            ],
            'movement_date' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The date of the movement',
            ],
        ];
    }
}
