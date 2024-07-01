<?php

namespace App\GraphQL\Types;

use App\Models\Inventory;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class InventoryType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Inventory',
        'description' => 'An inventory record',
        'model' => Inventory::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the inventory record',
            ],
            'product' => [
                'type' => GraphQL::type('Product'),
                'description' => 'The product in the inventory',
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
