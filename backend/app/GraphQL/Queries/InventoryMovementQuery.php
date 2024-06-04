<?php

namespace App\GraphQL\Queries;

use App\Models\InventoryMovement;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class InventoryMovementQuery extends Query
{
    protected $attributes = [
        'name' => 'inventoryMovement',
    ];

    public function type(): Type
    {
        return GraphQL::type('InventoryMovement');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The id of the inventory movement',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        return InventoryMovement::find($args['id']);
    }
}
