<?php

namespace App\GraphQL\Types;

use App\Models\InventoryMovement;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class InventoryMovementType extends GraphQLType
{
    protected $attributes = [
        'name' => 'InventoryMovement',
        'description' => 'An inventory movement record',
        'model' => InventoryMovement::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the inventory movement',
            ],
            'inventory' => [
                'type' => GraphQL::type('Inventory'),
                'description' => 'The inventory record associated with the movement',
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
