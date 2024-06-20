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
                'description' => 'The inventory related to the movement',
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
